<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Facility;
use App\Models\Category;

use Illuminate\Support\Facades\Log;

class WelcomeController extends Controller
{
    public function index(Request $request)
    {
        try {
            // 1. Statistik Utama
            $totalRooms = Room::count();
            $occupiedRooms = Room::where('status', 'occupied')->count();
            $vacantRooms = Room::where('status', 'empty')->count();
            $categories = Category::all();
            $facilities = Facility::all();

            // 2. Ambil Data Kamar untuk Gallery (Tambahkan Logika Search & Filter)
            // Variabel $rooms ini WAJIB ada agar view tidak error
            $rooms = Room::where('status', 'empty')
                ->when($request->search, function($query) use ($request) {
                    return $query->where('room_number', 'like', '%' . $request->search . '%')
                                 ->orWhere('location', 'like', '%' . $request->search . '%');
                })
                ->when($request->category, function($query) use ($request) {
                    // Jika user memilih tipe tertentu (bukan 'Semua')
                    if ($request->category && $request->category !== 'Semua') {
                        return $query->where('category_id', $request->category);
                    }
                })
                ->latest()
                ->paginate(6)
                ->withQueryString();

        } catch (\Throwable $e) {
            Log::error('WelcomeController index error: '.$e->getMessage());
            $totalRooms = 0;
            $occupiedRooms = 0;
            $vacantRooms = 0;
            $facilities = collect();
            $rooms = collect(); // Biar view tidak error jika database bermasalah
            $categories = Category::all();
        }

        // 3. Masukkan 'rooms' ke dalam compact
        return view('welcome', compact(
            'totalRooms', 
            'occupiedRooms', 
            'vacantRooms', 
            'facilities', 
            'rooms',
            'categories'
        ));
    }

    public function ask(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('user.ai.chat.page')
                ->with('status', 'Silakan ajukan pertanyaan Anda di AI Chat.');
        }
        return redirect()->guest(route('login'))
            ->with('status', 'Silakan login untuk bertanya lewat sistem (AI Chat).');
    }
}