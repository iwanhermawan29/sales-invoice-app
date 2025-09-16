<?php
// app/Http/Controllers/ReceivableController.php

namespace App\Http\Controllers;

use App\Models\Receivable;
use App\Models\Invoice;
use Illuminate\Http\Request;

class ReceivableController extends Controller
{
    public function index(Request $request)
    {
        $query = Receivable::with('invoice.deliveryOrder.salesOrder.customer');

        if ($q = $request->q) {
            $query->whereHas(
                'invoice',
                fn($q2) =>
                $q2->where('invoice_number', 'like', "%{$q}%")
            );
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $receivables = $query
            ->orderBy('due_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('receivables.index', compact('receivables'));
    }

    public function create()
    {
        $invoices = Invoice::with('deliveryOrder.salesOrder.customer')->get();
        return view('receivables.create', compact('invoices'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_id'  => 'required|exists:invoices,id',
            'due_date'    => 'required|date',
            'amount_due'  => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'status'      => 'required|in:unpaid,partial,paid',
        ]);

        $data['amount_paid'] = $data['amount_paid'] ?? 0;

        Receivable::create($data);

        return redirect()
            ->route('receivables.index')
            ->with('success', 'Receivable berhasil dibuat.');
    }

    public function show(Receivable $receivable)
    {
        // load via deliveryOrder → salesOrder → customer
        $receivable->load('invoice.deliveryOrder.salesOrder.customer');
        return view('receivables.show', compact('receivable'));
    }

    public function edit(Receivable $receivable)
    {
        // load via deliveryOrder → salesOrder → customer
        $receivable->load('invoice.deliveryOrder.salesOrder.customer');
        $invoices = Invoice::with('deliveryOrder.salesOrder.customer')->get();
        return view('receivables.edit', compact('receivable', 'invoices'));
    }
    public function update(Request $request, Receivable $receivable)
    {
        $data = $request->validate([
            'invoice_id'  => 'required|exists:invoices,id',
            'due_date'    => 'required|date',
            'amount_due'  => 'required|numeric|min:0',
            'amount_paid' => 'nullable|numeric|min:0',
            'status'      => 'required|in:unpaid,partial,paid',
        ]);

        $data['amount_paid'] = $data['amount_paid'] ?? 0;

        $receivable->update($data);

        return redirect()
            ->route('receivables.index')
            ->with('success', 'Receivable berhasil diperbarui.');
    }

    public function destroy(Receivable $receivable)
    {
        $receivable->delete();

        return redirect()
            ->route('receivables.index')
            ->with('success', 'Receivable berhasil dihapus.');
    }

    public function print(Request $request)
    {
        $query = Receivable::with('invoice.deliveryOrder.salesOrder.customer');
        if ($q = $request->q) {
            $query->whereHas(
                'invoice',
                fn($q2) =>
                $q2->where('invoice_number', 'like', "%{$q}%")
            );
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $receivables = $query->orderBy('due_date', 'desc')->get();
        return view('receivables.print', compact('receivables'));
    }
}
