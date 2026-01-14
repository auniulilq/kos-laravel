<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Category;
use App\Models\Facility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function index(Request $request)
    {
        // PERBAIKAN: Tambahkan with('category') agar tidak error "null name"
        $query = Room::with(['user', 'category']); 

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where('room_number', 'like', "%$q%");
        }

        $rooms = $query->latest()->paginate(6)->appends($request->query());

        $stats = [
            'total' => Room::count(),
            'empty' => Room::where('status', 'empty')->count(),
            'occupied' => Room::where('status', 'occupied')->count(),
            'maintenance' => Room::where('status', 'maintenance')->count(),
        ];

        return view('admin.rooms.index', compact('rooms', 'stats'));
    }

    public function create()
    {
        $categories = Category::all();
        $facilities = Facility::all();
        return view('admin.rooms.create', compact('categories', 'facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|unique:rooms',
            'category_id' => 'required|exists:categories,id', // PERBAIKAN: Tambahkan validasi kategori
            'price' => 'required|numeric|min:0',
            'area'        => 'nullable|string',      // Tambahkan ini
        'electricity' => 'nullable|string',     // Tambahkan ini
        'capacity'    => 'nullable|integer',
            'status' => 'required|in:empty,occupied,maintenance',
            'facilities' => 'array',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('rooms', 'public');
        }

        // PERBAIKAN: Jika Model sudah pakai $casts di Room.php, tidak perlu json_encode manual
        // Tapi jika belum, biarkan seperti ini
        $validated['facilities'] = $request->facilities ?? []; 

        Room::create($validated);

        return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil ditambahkan');
    }

    public function edit(Room $room)
    {
        // PERBAIKAN: Tambahkan $categories agar tidak error "Undefined Variable"
        $categories = Category::all(); 
        $facilities = Facility::all(); // Pakai Facility::all() jika scopeActive bermasalah
        
        return view('admin.rooms.edit', compact('room', 'categories', 'facilities'));
    }

    public function update(Request $request, Room $room)
{
    $data = $request->validate([
        'room_number' => 'required',
        'category_id' => 'required',
        'price'       => 'required|numeric',
        'status'      => 'required',
        'area'        => 'nullable',
        'electricity' => 'nullable',
        'capacity'    => 'nullable',
        'type'        => 'nullable', // Jika Anda masih pakai field type
        'description' => 'nullable',
        'image'       => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    // Handle upload gambar jika ada yang baru
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('rooms', 'public');
    }

    // PENTING: Ubah array facilities menjadi JSON sebelum disimpan
    $data['facilities'] = json_encode($request->facilities);

    $room->update($data);

    return redirect()->route('admin.rooms.index')->with('success', 'Kamar berhasil diperbarui!');
    
}
    public function destroy(Room $room)
    {
        if ($room->status === 'occupied') {
            return back()->with('error', 'Tidak bisa menghapus kamar yang sedang ditempati');
        }

        if ($room->image) {
            Storage::disk('public')->delete($room->image);
        }

        $room->delete();

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Kamar berhasil dihapus');
    }

    public function updateStatus(Request $request, Room $room)
    {
        $validated = $request->validate([
            'status' => 'required|in:empty,occupied,maintenance',
        ]);

        $room->update($validated);

        return back()->with('success', 'Status kamar berhasil diupdate');
    }

    public function show(Room $room)
{
    // Mengambil semua fasilitas untuk ditampilkan di halaman detail jika diperlukan
    // tapi biasanya cukup mengirimkan $room saja karena relasi sudah ada di model
    $facilities = \App\Models\Facility::all();

    return view('admin.rooms.show', compact('room', 'facilities'));
}
    
}
