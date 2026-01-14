<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Menampilkan daftar kategori
   public function index()
{
    // Menggunakan withCount agar Laravel menghitung jumlah kamar secara otomatis
    $categories = Category::withCount('rooms')->latest()->paginate(6)->appends(request()->query());
    return view('admin.categories.index', compact('categories'));
}

    // Menyimpan kategori baru
    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'slug' => 'required|unique:categories,slug',
    ]);

    Category::create($validated);
    return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat!');
}

    // Menghapus kategori
    public function destroy(Category $category)
    {
        // Opsional: Cek jika kategori masih digunakan oleh kamar
        if ($category->rooms()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih digunakan oleh beberapa kamar.');
        }

        $category->delete();
        return back()->with('success', 'Kategori berhasil dihapus!');
    }
    public function create()
    {
        return view('admin.categories.create');
    }
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // 3. TAMBAHKAN INI: Fungsi untuk memproses update data
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan slug unik, tapi abaikan ID kategori ini sendiri saat update
            'slug' => 'required|unique:categories,slug,' . $category->id,
        ]);

        $category->update($validated);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diupdate!');
    }
}