<?php

namespace App\Http\Controllers;

use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalesOrderController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->input('q');
        $status = $request->input('status');

        $query = SalesOrder::with('customer');
        if ($q) {
            $query->where('so_number', 'like', "%{$q}%")
                ->orWhereHas('customer', fn($q2) =>
                $q2->where('name', 'like', "%{$q}%"));
        }
        if ($status) {
            $query->where('status', $status);
        }

        $salesOrders = $query->orderBy('order_date', 'desc')
            ->paginate(15)
            ->withQueryString();
        return view('sales-orders.index', compact('salesOrders'));
    }

    public function create()
    {
        $last = SalesOrder::orderBy('id', 'desc')->first();
        $num = $last ? intval(substr($last->so_number, 2)) + 1 : 1;
        $soNumber = 'SO' . str_pad($num, 6, '0', STR_PAD_LEFT);
        $customers = Customer::active()->get();
        $items = Item::active()->get();
        return view('sales-orders.create', compact('soNumber', 'customers', 'items'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'so_number'   => 'required|unique:sales_orders,so_number',
            'customer_id' => 'required|exists:customers,id',
            'order_date'  => 'required|date',
            'lines'       => 'required|array|min:1',
            'lines.*.item_id'    => 'required|exists:items,id',
            'lines.*.qty'        => 'required|integer|min:1',
            'lines.*.unit_price' => 'required|numeric|min:0',
        ]);
        $so = SalesOrder::create([
            'so_number' => $data['so_number'],
            'customer_id' => $data['customer_id'],
            'created_by' => Auth::id(),
            'order_date' => $data['order_date'],
            'status' => 'draft',
        ]);
        foreach ($data['lines'] as $line) {
            SalesOrderItem::create([
                'sales_order_id' => $so->id,
                'item_id' => $line['item_id'],
                'quantity' => $line['qty'],
                'unit_price' => $line['unit_price'],
                'subtotal' => $line['qty'] * $line['unit_price'],
            ]);
        }
        return redirect()->route('sales-orders.index')->with('success', 'Sales Order berhasil dibuat.');
    }

    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load('items.item', 'customer');
        return view('sales-orders.show', compact('salesOrder'));
    }

    public function edit(SalesOrder $salesOrder)
    {
        $salesOrder->load('items');
        $customers = Customer::active()->get();
        $items = Item::active()->get();
        return view('sales-orders.edit', compact('salesOrder', 'customers', 'items'));
    }

    public function update(Request $request, SalesOrder $salesOrder)
    {
        $data = $request->validate([
            'order_date' => 'required|date',
            'customer_id' => 'required|exists:customers,id',
            'lines' => 'required|array|min:1',
            'lines.*.item_id' => 'required|exists:items,id',
            'lines.*.qty' => 'required|integer|min:1',
            'lines.*.unit_price' => 'required|numeric|min:0',
        ]);
        $salesOrder->update(['order_date' => $data['order_date'], 'customer_id' => $data['customer_id']]);
        $salesOrder->items()->delete();
        foreach ($data['lines'] as $line) {
            SalesOrderItem::create([
                'sales_order_id' => $salesOrder->id,
                'item_id' => $line['item_id'],
                'quantity' => $line['qty'],
                'unit_price' => $line['unit_price'],
                'subtotal' => $line['qty'] * $line['unit_price'],
            ]);
        }
        return redirect()->route('sales-orders.index')->with('success', 'Sales Order berhasil diperbarui.');
    }

    public function destroy(SalesOrder $salesOrder)
    {
        $salesOrder->delete();
        return redirect()->route('sales-orders.index')
            ->with('success', 'Sales Order berhasil dihapus.');
    }

    public function print(Request $request)
    {
        $query = SalesOrder::with('customer');

        if ($q = $request->q) {
            $query->where('so_number', 'like', "%{$q}%")
                ->orWhereHas(
                    'customer',
                    fn($q2) =>
                    $q2->where('name', 'like', "%{$q}%")
                );
        }

        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $salesOrders = $query
            ->orderBy('order_date', 'desc')
            ->get();

        return view('sales-orders.print', compact('salesOrders'));
    }
}
