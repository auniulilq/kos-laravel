<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'user')
            ->with('room');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhere('email', 'like', "%$q%")
                    ->orWhere('phone', 'like', "%$q%");
            });
        }

        if ($request->filled('has_room')) {
            if ($request->has_room === 'yes') {
                $query->whereHas('room');
            } elseif ($request->has_room === 'no') {
                $query->doesntHave('room');
            }
        }
        
        $users = $query->latest()
            ->paginate(perPage: 6)
            ->appends($request->query());
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $emptyRooms = Room::where('status', 'empty')->get();
        return view('admin.users.create', compact('emptyRooms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|string',
            'address' => 'required|string',
            'room_id' => 'nullable|exists:rooms,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'user';
        $validated['whatsapp_opt_in'] = $request->has('whatsapp_opt_in');

        $user = User::create($validated);

        // Assign room if selected
        if ($request->room_id) {
            $room = Room::find($request->room_id);
            $room->update([
                'user_id' => $user->id,
                'status' => 'occupied',
            ]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Penyewa berhasil ditambahkan');
    }

    public function show(User $user)
    {
        $user->load('room', 'payments', 'serviceRequests');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $emptyRooms = Room::where('status', 'empty')
            ->orWhere('user_id', $user->id)
            ->get();
        
        return view('admin.users.edit', compact('user', 'emptyRooms'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'required|string',
            'address' => 'required|string',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }

        $validated['whatsapp_opt_in'] = $request->has('whatsapp_opt_in');

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data penyewa berhasil diupdate');
    }

    public function destroy(User $user)
    {
        // Free up the room first
        if ($user->room) {
            $user->room->update([
                'user_id' => null,
                'status' => 'empty',
            ]);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Penyewa berhasil dihapus');
    }

    public function assignRoom(Request $request, User $user)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $room = Room::find($validated['room_id']);

        if ($room->status !== 'empty') {
            return back()->with('error', 'Kamar sudah ditempati');
        }

        // Free old room if exists
        if ($user->room) {
            $user->room->update([
                'user_id' => null,
                'status' => 'empty',
            ]);
        }

        // Assign new room
        $room->update([
            'user_id' => $user->id,
            'status' => 'occupied',
        ]);

        return back()->with('success', 'Kamar berhasil di-assign');
    }
}
