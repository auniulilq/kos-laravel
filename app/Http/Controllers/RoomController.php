<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // Method untuk menampilkan semua kamar (Halaman Welcome)
    public function index()
    {
        $totalRooms = 10;
        $occupiedRooms = 6;
        $vacantRooms = 4;

        return view('welcome', compact('totalRooms', 'occupiedRooms', 'vacantRooms'));
    }

    // Method untuk menampilkan detail satu kamar
    public function show($id)
{
    // Cari data kamar berdasarkan ID
    $room = Room::findOrFail($id);

    // Kirim variabel $room ke view
    return view('rooms.show', compact('room'));
}
}