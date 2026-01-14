<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Menampilkan daftar kamar untuk user
     */
    public function index()
    {
        $user = auth()->user();
        $rooms = Room::with('category', 'user')
            ->where('user_id', $user->id)
            ->orWhere('status', 'empty')
            ->orderBy('room_number')
            ->paginate(6);

        return view('user.rooms.index', compact('rooms'));
    }

    /**
     * Menampilkan detail kamar
     */
    public function show(Room $room)
    {
        // Pastikan user hanya bisa melihat kamar miliknya atau kamar kosong
        if ($room->user_id !== auth()->id() && $room->status !== 'empty') {
            abort(403, 'Anda tidak memiliki akses ke kamar ini.');
        }

        $room->load('category', 'user');
        
        return view('user.rooms.show', compact('room'));
    }
}