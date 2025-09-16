<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\EventTarget;
use App\Models\User;
use Illuminate\Http\Request;

class EventTargetController extends Controller
{


    /** Display a listing of event targets. */
    public function index()
    {
        $eventTargets = EventTarget::with('user')->orderByDesc('year')->paginate(10);
        return view('event_targets.index', compact('eventTargets'));
    }

    /** Show the form for creating a new event target. */
    public function create()
    {
        // Ambil daftar agen (role=agent)
        $agents = User::whereHas('role', fn($q) => $q->where('name', 'agent'))->get();
        return view('event_targets.create', compact('agents'));
    }

    /** Store a newly created event target in storage. */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'event_name'    => 'required|string|max:255',
            'year'          => 'required|digits:4|integer',
            'target_amount' => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        EventTarget::create($request->only('user_id', 'event_name', 'year', 'target_amount', 'notes'));

        return redirect()->route('event-targets.index')
            ->with('success', 'Event target berhasil dibuat.');
    }

    /** Show the form for editing the specified event target. */
    public function edit(EventTarget $eventTarget)
    {
        $agents = User::whereHas('role', fn($q) => $q->where('name', 'agent'))->get();
        return view('event_targets.edit', compact('eventTarget', 'agents'));
    }

    /** Update the specified event target in storage. */
    public function update(Request $request, EventTarget $eventTarget)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'event_name'    => 'required|string|max:255',
            'year'          => 'required|digits:4|integer',
            'target_amount' => 'required|numeric|min:0',
            'notes'         => 'nullable|string',
        ]);

        $eventTarget->update($request->only('user_id', 'event_name', 'year', 'target_amount', 'notes'));

        return redirect()->route('event-targets.index')
            ->with('success', 'Event target berhasil diperbarui.');
    }

    /** Remove the specified event target from storage. */
    public function destroy(EventTarget $eventTarget)
    {
        $eventTarget->delete();

        return redirect()->route('event-targets.index')
            ->with('success', 'Event target berhasil dihapus.');
    }
}
