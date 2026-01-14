@extends('layouts.app')

@section('title', 'Tambah Kamar')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 25px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    {{-- Breadcrumb --}}
    <div style="margin-bottom: 25px;">
        <a href="{{ route('admin.rooms.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
            <span style="font-size: 18px;">←</span> Kembali ke Daftar Kamar
        </a>
    </div>

    <div style="background: white; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); overflow: hidden;">
        {{-- Header Form --}}
        <div style="padding: 30px; border-bottom: 1px solid #f1f5f9; background: #fafafa;">
            <h1 style="font-size: 26px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
                Tambah Kamar Baru
            </h1>
            <p style="color: #64748b; margin-top: 8px; font-size: 15px;">Lengkapi detail unit di bawah ini dengan benar untuk dipublikasikan.</p>
        </div>

        <form action="{{ route('admin.rooms.store') }}" method="POST" enctype="multipart/form-data" style="padding: 35px;">
            @csrf

            <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 40px;">
                
                {{-- Kolom Kiri: Informasi Utama --}}
                <div>
                    <h3 style="font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <span style="background: #e0f2fe; color: #0ea5e9; padding: 4px 8px; border-radius: 6px; font-size: 12px;">1</span> Informasi Dasar
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Nomor Kamar <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="room_number" value="{{ old('room_number') }}" required placeholder="Contoh: A-01"
                                   style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; outline: none; font-size: 14px; transition: 0.3s;" onfocus="this.style.borderColor='#3b82f6'">
                        </div>

                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Pilih Kategori <span style="color: #ef4444;">*</span></label>
                            <select name="category_id" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; outline: none; background: white; font-size: 14px; color: #475569; cursor: pointer;">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Harga Sewa / Bulan <span style="color: #ef4444;">*</span></label>
                            <div style="position: relative;">
                                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-weight: 600; font-size: 14px;">Rp</span>
                                <input type="number" name="price" value="{{ old('price') }}" required placeholder="800000"
                                       style="width: 100%; padding: 12px 15px 12px 40px; border-radius: 12px; border: 1.5px solid #e2e8f0; outline: none; font-size: 14px;">
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Status Awal <span style="color: #ef4444;">*</span></label>
                            <select name="status" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; outline: none; background: white; font-size: 14px; color: #475569;">
                                <option value="empty" {{ old('status') === 'empty' ? 'selected' : '' }}>Kosong (Siap Huni)</option>
                                <option value="occupied" {{ old('status') === 'occupied' ? 'selected' : '' }}>Terisi</option>
                                <option value="maintenance" {{ old('status') === 'maintenance' ? 'selected' : '' }}>Dalam Perbaikan</option>
                            </select>
                        </div>
                    </div>

                    <h3 style="font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin: 35px 0 20px; display: flex; align-items: center; gap: 8px;">
                        <span style="background: #fef3c7; color: #d97706; padding: 4px 8px; border-radius: 6px; font-size: 12px;">2</span> Spesifikasi Kamar
                    </h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-bottom: 25px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Luas (m)</label>
                            <input type="text" name="area" placeholder="3x4" value="{{ old('area') }}"
                                style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc; font-size: 14px;">
                        </div>

                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Listrik</label>
                            <select name="electricity" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc; font-size: 13px; font-weight: 600; color: #475569;">
                                <option value="Token (Mandiri)">Token (Mandiri)</option>
                                <option value="Termasuk Sewa">Termasuk Sewa</option>
                                <option value="Pascabayar">Pascabayar</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Kapasitas</label>
                            <input type="number" name="capacity" placeholder="1" value="{{ old('capacity', 1) }}"
                                style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc; font-size: 14px;">
                        </div>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Deskripsi Tambahan</label>
                        <textarea name="description" rows="4" placeholder="Contoh: Dekat jendela, kamar mandi dalam, baru dicat..." 
                                  style="width: 100%; padding: 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; outline: none; font-size: 14px; resize: none; transition: 0.3s;" onfocus="this.style.borderColor='#3b82f6'">{{ old('description') }}</textarea>
                    </div>
                </div>

                {{-- Kolom Kanan: Media & Fasilitas --}}
                <div style="background: #f8fafc; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9;">
                    <h3 style="font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <span style="background: #dcfce7; color: #166534; padding: 4px 8px; border-radius: 6px; font-size: 12px;">3</span> Media & Fasilitas
                    </h3>

                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 12px;">Foto Kamar</label>
                    <div style="border: 2px dashed #cbd5e1; padding: 25px; border-radius: 16px; text-align: center; background: white; transition: 0.3s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.background='#f0f7ff'" onmouseout="this.style.borderColor='#cbd5e1'; this.style.background='white'">
                        <div style="font-size: 32px; margin-bottom: 12px;">🖼️</div>
                        <input type="file" name="image" accept="image/*" style="font-size: 12px; color: #64748b; width: 100%;">
                        <p style="font-size: 11px; color: #94a3b8; margin-top: 10px; font-weight: 500;">Format: JPG, PNG (Maks. 2MB)</p>
                    </div>

                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-top: 30px; margin-bottom: 15px;">Fasilitas Tersedia</label>
                    <div style="display: grid; grid-template-columns: 1fr; gap: 10px; max-height: 300px; overflow-y: auto; padding-right: 5px;">
                        @foreach($facilities as $facility)
                        <label style="display: flex; align-items: center; gap: 12px; cursor: pointer; font-size: 14px; color: #475569; background: white; padding: 12px; border-radius: 10px; border: 1px solid #e2e8f0; transition: 0.2s;" onmouseover="this.style.borderColor='#3b82f6'">
                            <input type="checkbox" name="facilities[]" value="{{ $facility->id }}" 
                                   style="width: 18px; height: 18px; cursor: pointer; accent-color: #3b82f6;">
                            <span style="font-weight: 600;">{{ $facility->name }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #f1f5f9; display: flex; gap: 15px; justify-content: flex-end;">
                <a href="{{ route('admin.rooms.index') }}" style="text-decoration: none; padding: 12px 30px; border-radius: 12px; color: #64748b; font-weight: 700; font-size: 14px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'">
                    Batal
                </a>
                <button type="submit" style="background: #1e293b; color: white; border: none; padding: 14px 35px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.2);" onmouseover="this.style.background='#000'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#1e293b'; this.style.transform='translateY(0)'">
                    Simpan 
                </button>
            </div>
        </form>
    </div>
</div>
@endsection