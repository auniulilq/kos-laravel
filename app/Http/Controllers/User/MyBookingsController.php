<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class MyBookingsController extends Controller
{
    /**
     * Menampilkan daftar booking milik user
     */
    public function index()
    {
        $user = auth()->user();
        
        $bookings = Booking::with(['room', 'room.category'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        return view('user.my-bookings', compact('bookings'));
    }

    /**
     * Menampilkan detail booking
     */
    public function show(Booking $booking)
    {
        // Pastikan user hanya bisa melihat booking miliknya
        if ($booking->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke booking ini.');
        }

        $booking->load(['room', 'room.category', 'user']);

        return view('user.my-bookings-show', compact('booking'));
    }
}