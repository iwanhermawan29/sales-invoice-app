<?php
// app/Http/Controllers/CustomerController.php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%')
                ->orWhere('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }
        $customers = $query->orderBy('name')
            ->paginate(15)
            ->withQueryString();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        // generate code similar to items
        $last = Customer::orderBy('code', 'desc')->first();
        $num = $last ? intval(substr($last->code, 3)) + 1 : 1;
        $code = 'CST' . str_pad($num, 4, '0', STR_PAD_LEFT);
        return view('customers.create', compact('code'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|unique:customers,code',
            'name' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        Customer::create($data);
        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dibuat.');
    }

    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'code' => 'required|unique:customers,code,' . $customer->id,
            'name' => 'required|string',
            'address' => 'nullable|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
        ]);
        $data['is_active'] = $request->has('is_active') ? 1 : 0;
        $customer->update($data);
        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }

    public function print(Request $request)
    {
        $query = Customer::query();
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%')
                ->orWhere('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('active')) {
            $query->where('is_active', $request->active);
        }
        $customers = $query->orderBy('name')->get();
        return view('customers.print', compact('customers'));
    }
}
