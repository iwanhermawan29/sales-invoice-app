<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\DeliveryOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('deliveryOrder.salesOrder.customer');

        if ($q = $request->q) {
            $query->where('invoice_number', 'like', "%{$q}%")
                ->orWhereHas(
                    'deliveryOrder.salesOrder',
                    fn($q2) =>
                    $q2->where('so_number', 'like', "%{$q}%")
                );
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $invoices = $query->orderBy('invoice_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        // generate invoice number INV0001, INV0002...
        $last = Invoice::orderBy('id', 'desc')->first();
        $num  = $last ? intval(substr($last->invoice_number, 3)) + 1 : 1;
        $invNumber = 'INV' . str_pad($num, 4, '0', STR_PAD_LEFT);

        $deliveryOrders = DeliveryOrder::where('status', 'delivered')->get();

        return view('invoices.create', compact('invNumber', 'deliveryOrders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_number'     => 'required|unique:invoices,invoice_number',
            'delivery_order_id'  => 'required|exists:delivery_orders,id',
            'invoice_date'       => 'required|date',
            'due_date'           => 'nullable|date|after_or_equal:invoice_date',
        ]);

        $data['created_by'] = Auth::id();
        $data['status']     = 'open';

        // Calculate total_amount from the lines (qty * unit_price)
        $data['total_amount'] = collect($request->lines)
            ->sum(fn($line) => ($line['qty'] ?? 0) * ($line['unit_price'] ?? 0));

        DB::transaction(function () use ($data, $request) {
            $inv = Invoice::create($data);

            foreach ($request->lines as $line) {
                if (! empty($line['sales_order_item_id'])) {
                    InvoiceItem::create([
                        'invoice_id'          => $inv->id,
                        'sales_order_item_id' => $line['sales_order_item_id'],
                        'amount'              => ($line['qty'] ?? 0) * ($line['unit_price'] ?? 0),
                    ]);
                }
            }
        });

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice berhasil dibuat.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('deliveryOrder.salesOrder.customer', 'items.salesOrderItem.item');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        // eager‐load invoice items → sales order item → item
        $invoice->load('items.salesOrderItem.item');

        // eager‐load DOs → customer + DO items → sales order item → item
        $deliveryOrders = DeliveryOrder::with([
            'salesOrder.customer',
            'items.salesOrderItem.item'
        ])
            ->where('status', 'delivered')
            ->get();

        return view('invoices.edit', compact('invoice', 'deliveryOrders'));
    }
    public function update(Request $request, Invoice $invoice)
    {
        // 1) Validate
        $data = $request->validate([
            'delivery_order_id' => 'required|exists:delivery_orders,id',
            'invoice_date'      => 'required|date',
            'due_date'          => 'nullable|date|after_or_equal:invoice_date',
        ]);

        // 2) Compute new total
        $total = collect($request->lines)
            ->sum(fn($ln) => ($ln['qty'] ?? 0) * ($ln['unit_price'] ?? 0));

        $data['total_amount'] = $total;

        // 3) Persist in transaction
        DB::transaction(function () use ($invoice, $data, $request) {
            // Update header
            $invoice->update($data);

            // Remove old lines
            $invoice->items()->delete();

            // Recreate from inputs
            foreach ($request->lines as $ln) {
                if (! empty($ln['sales_order_item_id'])) {
                    $invoice->items()->create([
                        'sales_order_item_id' => $ln['sales_order_item_id'],
                        'amount'              => ($ln['qty'] ?? 0) * ($ln['unit_price'] ?? 0),
                    ]);
                }
            }
        });

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice berhasil diperbarui.');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice berhasil dihapus.');
    }

    public function print(Request $request)
    {
        $query = Invoice::with('deliveryOrder.salesOrder.customer');
        if ($q = $request->q) {
            $query->where('invoice_number', 'like', "%{$q}%")
                ->orWhereHas(
                    'deliveryOrder.salesOrder',
                    fn($q2) =>
                    $q2->where('so_number', 'like', "%{$q}%")
                );
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }
        $invoices = $query->orderBy('invoice_date', 'desc')->get();
        return view('invoices.print', compact('invoices'));
    }
}
