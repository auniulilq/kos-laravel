@extends('layouts.app')

@section('title', 'Edit Kategori')

@section('content')
<div style="max-width: 600px; margin: 40px auto; padding: 20px; font-family: 'Inter', sans-serif;">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('admin.categories.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700;">← Kembali</a>
    </div>

    <div style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05); padding: 35px;">
        <h2 style="font-size: 24px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Edit Kategori</h2>
        <p style="color: #64748b; margin-bottom: 30px; font-size: 14px;">Perbarui informasi kategori <strong>{{ $category->name }}</strong>.</p>

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf @method('PUT')
            {{-- Input Nama --}}
<div style="margin-bottom: 20px;">
    <label>Nama Kategori</label>
    <input type="text" name="name" id="name" placeholder="Contoh: VIP Exclusive" required
        style="width: 100%; padding: 14px 18px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc;">
</div>

{{-- Input Slug (Tambahkan input ini agar user bisa melihat/mengedit jika perlu) --}}
<div style="margin-bottom: 20px;">
    <label>Slug (URL)</label>
    <input type="text" name="slug" id="slug" readonly
        style="width: 100%; padding: 14px 18px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #e2e8f0; color: #64748b;" 
        placeholder="Otomatis terisi...">
    <small style="color: #94a3b8;">Slug digunakan untuk alamat URL yang ramah SEO.</small>
</div>

{{-- Script Otomatisasi --}}
<script>
    const nameInput = document.querySelector('#name');
    const slugInput = document.querySelector('#slug');

    nameInput.addEventListener('keyup', function() {
        let preslug = nameInput.value;
        preslug = preslug.replace(/[^a-zA-Z0-9\s]/g, ""); // Hapus karakter spesial
        preslug = preslug.toLowerCase();
        preslug = preslug.replace(/\s+/g, '-'); // Ganti spasi dengan tanda hubung
        slugInput.value = preslug;
    });
</script>

            <button type="submit" style="width: 100%; background: #1e293b; color: white; border: none; padding: 15px; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#000'">
                Perbarui Kategori
            </button>
        </form>
    </div>
</div>
@endsection