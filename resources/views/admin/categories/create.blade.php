@extends('layouts.app')

@section('title', 'Tambah Kategori')

@section('content')
<div style="max-width: 500px; margin: 40px auto; padding: 20px; font-family: 'Inter', sans-serif;">
    <div style="margin-bottom: 25px;">
        <a href="{{ route('admin.categories.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali
        </a>
    </div>

    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05); padding: 40px;">
        <h2 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0 0 8px 0; letter-spacing: -0.5px;">Tambah Kategori</h2>
        <p style="color: #64748b; margin-bottom: 30px; font-size: 14px; line-height: 1.5;">Masukkan nama kategori baru untuk mengelompokkan unit kamar.</p>

        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            
            {{-- Input Slug (Disembunyikan) --}}
            <input type="hidden" name="slug" id="slug">

            {{-- Input Nama --}}
            <div style="margin-bottom: 30px;">
                <label style="display: block; font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px; letter-spacing: 0.5px;">Nama Kategori</label>
                <input type="text" name="name" id="name" placeholder="Contoh: VIP Exclusive" required autofocus
                    style="width: 100%; padding: 16px 20px; border-radius: 14px; border: 1.5px solid #f1f5f9; background: #f8fafc; outline: none; transition: 0.3s; font-size: 15px; color: #1e293b;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)'; "
                    onblur="this.style.borderColor='#f1f5f9'; this.style.boxShadow='none'; this.style.background='#f8fafc';">
                
                @error('name') 
                    <span style="color: #ef4444; font-size: 12px; margin-top: 8px; display: flex; align-items: center; gap: 4px;">
                        <small>⚠️</small> {{ $message }}
                    </span> 
                @enderror
                @error('slug') 
                    <span style="color: #ef4444; font-size: 12px; margin-top: 8px; display: block;">Slug Error: {{ $message }}</span> 
                @enderror
            </div>

            <button type="submit" style="width: 100%; background: #3b82f6; color: white; border: none; padding: 16px; border-radius: 14px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);" onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-1px)';" onmousedown="this.style.transform='translateY(0)';" onmouseout="this.style.background='#3b82f6'; this.style.transform='translateY(0)';">
                Simpan Kategori
            </button>
        </form>
    </div>
</div>

<script>
    const nameInput = document.querySelector('#name');
    const slugInput = document.querySelector('#slug');

    nameInput.addEventListener('input', function() {
        let preslug = nameInput.value;
        preslug = preslug.toLowerCase();
        preslug = preslug.replace(/[^a-z0-9\s]/g, ""); 
        preslug = preslug.replace(/\s+/g, '-'); 
        slugInput.value = preslug;
    });
</script>
@endsection