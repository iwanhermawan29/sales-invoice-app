<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Tampilkan daftar user
    public function index(Request $request)
    {
        $q      = trim((string) $request->input('q', ''));      // cari nama/email/agency/kota
        $status = $request->input('status', '');                // 0|1|2 (pending/approved/rejected)
        $sort   = (string) $request->input('sort', 'name');     // name|email|created_at
        $dir    = strtolower((string) $request->input('dir', 'asc')); // asc|desc

        if (!in_array($sort, ['name', 'email', 'created_at'], true)) $sort = 'name';
        if (!in_array($dir, ['asc', 'desc'], true)) $dir = 'asc';

        $users = User::query()
            ->with('role')
            ->when($q !== '', function ($qr) use ($q) {
                $qr->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('agency_name', 'like', "%{$q}%")
                        ->orWhere('kota', 'like', "%{$q}%"); // <— cari juga di kota
                });
            })
            ->when($status !== '' && $status !== null, fn($qr) => $qr->where('profile_status', (int) $status))
            ->orderBy($sort, $dir)
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        $statuses = [0 => 'Pending', 1 => 'Approved', 2 => 'Rejected'];

        return view('users.index', [
            'users'    => $users,
            'statuses' => $statuses,
            'q'        => $q,
            'status'   => $status,
            'sort'     => $sort,
            'dir'      => $dir,
        ]);
    }

    // Form tambah user
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    // Simpan user baru
    public function store(StoreUserRequest $request)
    {
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role_id'  => $request->role_id,
            'kota'     => $request->kota, // <— simpan kota
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Tampilkan detail user
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    // Form edit user
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    // Update user
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->only(['name', 'email', 'role_id', 'kota']); // <— izinkan kota
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Hapus user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
