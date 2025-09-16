<?php

namespace App\Http\Controllers;

use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
use App\Models\SalesOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = DeliveryOrder::with(['salesOrder.customer']);

        if ($q = $request->q) {
            $query->where('do_number', 'like', "%{$q}%")
                ->orWhereHas(
                    'salesOrder',
                    fn($q2) =>
                    $q2->where('so_number', 'like', "%{$q}%")
                );
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }

        $dos = $query->orderBy('delivery_date', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('delivery-orders.index', compact('dos'));
    }

    public function create()
    {
        // generate DO number: DO0001, DO0002, ...
        $last = DeliveryOrder::orderBy('id', 'desc')->first();
        $num  = $last ? intval(substr($last->do_number, 2)) + 1 : 1;
        $doNumber = 'DO' . str_pad($num, 4, '0', STR_PAD_LEFT);

        $salesOrders = SalesOrder::where('status', 'confirmed')->get();

        return view('delivery-orders.create', compact('doNumber', 'salesOrders'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'do_number'      => 'required|unique:delivery_orders,do_number',
            'sales_order_id' => 'required|exists:sales_orders,id',
            'delivery_date'  => 'required|date',
        ]);

        $data['created_by'] = Auth::id();
        $data['status']     = 'pending';

        DB::transaction(function () use ($data, $request) {
            $do = DeliveryOrder::create($data);

            foreach ($request->lines as $line) {
                if (isset($line['sales_order_item_id']) && $line['shipped_qty'] > 0) {
                    DeliveryOrderItem::create([
                        'delivery_order_id'   => $do->id,
                        'sales_order_item_id' => $line['sales_order_item_id'],
                        'shipped_qty'         => $line['shipped_qty'],
                    ]);
                }
            }
        });

        return redirect()
            ->route('delivery-orders.index')
            ->with('success', 'Delivery Order berhasil dibuat.');
    }

    public function show(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load('salesOrder.customer', 'items.salesOrderItem.item');
        return view('delivery-orders.show', compact('deliveryOrder'));
    }

    public function edit(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->load('items');
        $do = $deliveryOrder;
        $salesOrders = SalesOrder::where('status', 'confirmed')->get();
        return view('delivery-orders.edit', compact('do', 'salesOrders'));
    }

    public function update(Request $request, DeliveryOrder $deliveryOrder)
    {
        $data = $request->validate([
            'delivery_date'  => 'required|date',
            'status'         => 'required|in:pending,shipped,delivered',
        ]);

        DB::transaction(function () use ($deliveryOrder, $data, $request) {
            $deliveryOrder->update($data);
            // replace detail
            $deliveryOrder->items()->delete();
            foreach ($request->lines as $line) {
                if (isset($line['sales_order_item_id']) && $line['shipped_qty'] > 0) {
                    DeliveryOrderItem::create([
                        'delivery_order_id'   => $deliveryOrder->id,
                        'sales_order_item_id' => $line['sales_order_item_id'],
                        'shipped_qty'         => $line['shipped_qty'],
                    ]);
                }
            }
        });

        return redirect()
            ->route('delivery-orders.index')
            ->with('success', 'Delivery Order berhasil diperbarui.');
    }

    public function destroy(DeliveryOrder $deliveryOrder)
    {
        $deliveryOrder->delete();
        return redirect()
            ->route('delivery-orders.index')
            ->with('success', 'Delivery Order berhasil dihapus.');
    }

    public function print(Request $request)
    {
        $query = DeliveryOrder::with('salesOrder.customer');
        if ($q = $request->q) {
            $query->where('do_number', 'like', "%{$q}%")
                ->orWhereHas(
                    'salesOrder',
                    fn($q2) =>
                    $q2->where('so_number', 'like', "%{$q}%")
                );
        }
        if ($status = $request->status) {
            $query->where('status', $status);
        }
        $dos = $query->orderBy('delivery_date', 'desc')->get();
        return view('delivery-orders.print', compact('dos'));
    }
}
