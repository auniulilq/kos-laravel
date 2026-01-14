@extends('layouts.app')

@section('title', 'Detail Kamar ' . $room->room_number)

@section('content')
<div style="max-width: 1100px; margin: 0 auto; padding: 25px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    {{-- Top Action Bar --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <a href="{{ route('admin.rooms.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
                ← Kembali ke Daftar
            </a>
            <div style="display: flex; align-items: center; gap: 15px; margin-top: 10px;">
                <h1 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -1px;">Unit {{ $room->room_number }}</h1>
                <span style="background: white; border: 1.5px solid #e2e8f0; color: #64748b; padding: 4px 12px; border-radius: 10px; font-size: 13px; font-weight: 700;">{{ $room->category->name ?? 'Tanpa Kategori' }}</span>
            </div>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.rooms.edit', $room) }}" style="background: white; color: #1e293b; border: 1px solid #e2e8f0; text-decoration: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);" onmouseover="this.style.background='#f8fafc'">
                ✎ Edit Detail Kamar
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 380px 1fr; gap: 30px; align-items: start;">
        
        {{-- SISI KIRI: MEDIA & QUICK STATUS --}}
        <div>
            <div style="background: white; border-radius: 24px; border: 1px solid #f1f5f9; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
                @if($room->image)
                    <img src="{{ asset('storage/' . $room->image) }}" style="width: 100%; height: 280px; object-fit: cover; display: block;">
                @else
                    <div style="width: 100%; height: 280px; background: #f1f5f9; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8;">
                        <span style="font-size: 50px;">🖼️</span>
                        <span style="font-size: 13px; font-weight: 700; margin-top: 15px; letter-spacing: 1px;">BELUM ADA FOTO</span>
                    </div>
                @endif

                <div style="padding: 25px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px;">Kontrol Status Cepat</label>
                    <form action="{{ route('admin.rooms.updateStatus', $room) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <select name="status" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 14px; font-weight: 600; margin-bottom: 15px; color: #1e293b; cursor: pointer;">
                            <option value="empty" {{ $room->status === 'empty' ? 'selected' : '' }}>🟢 KOSONG (TERSEDIA)</option>
                            <option value="occupied" {{ $room->status === 'occupied' ? 'selected' : '' }}>🔴 TERISI</option>
                            <option value="maintenance" {{ $room->status === 'maintenance' ? 'selected' : '' }}>🟡 PERBAIKAN</option>
                        </select>
                        <button type="submit" style="width: 100%; background: #1e293b; color: white; border: none; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#000'">
                            Update Status
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Spesifikasi Ringkas --}}
            <div style="margin-top: 25px; display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div style="background: white; padding: 15px; border-radius: 16px; border: 1px solid #f1f5f9; text-align: center;">
                    <span style="display: block; font-size: 20px; margin-bottom: 5px;">📏</span>
                    <span style="display: block; font-size: 14px; font-weight: 800; color: #1e293b;">{{ $room->area ?? '-' }} m</span>
                    <span style="font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase;">Luas</span>
                </div>
                <div style="background: white; padding: 15px; border-radius: 16px; border: 1px solid #f1f5f9; text-align: center;">
                    <span style="display: block; font-size: 20px; margin-bottom: 5px;">⚡</span>
                    <span style="display: block; font-size: 14px; font-weight: 800; color: #1e293b;">{{ $room->electricity ?? '-' }}</span>
                    <span style="font-size: 11px; color: #94a3b8; font-weight: 600; text-transform: uppercase;">Listrik</span>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: DETAIL DATA --}}
        <div style="display: grid; gap: 25px;">
            
            {{-- Info Utama & Harga --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <span style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">Biaya Sewa</span>
                    <div style="display: flex; align-items: baseline; gap: 5px;">
                        <span style="font-size: 28px; font-weight: 800; color: #059669;">Rp{{ number_format($room->price, 0, ',', '.') }}</span>
                        <span style="font-size: 14px; color: #64748b; font-weight: 600;">/ bulan</span>
                    </div>
                </div>
                <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <span style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 10px;">Kapasitas Maksimal</span>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <span style="font-size: 28px; font-weight: 800; color: #1e293b;">{{ $room->capacity ?? '1' }}</span>
                        <span style="font-size: 14px; color: #64748b; font-weight: 600;">Orang</span>
                    </div>
                </div>
            </div>

            {{-- Info Penyewa (Hanya jika terisi) --}}
            @if($room->status === 'occupied')
            <div style="background: #eff6ff; border: 1.5px solid #dbeafe; border-radius: 20px; padding: 25px; display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; align-items: center; gap: 20px;">
                    <div style="width: 60px; height: 60px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; font-weight: 800;">
                        {{ substr($room->user->name ?? '?', 0, 1) }}
                    </div>
                    <div>
                        <span style="display: block; font-size: 11px; font-weight: 800; color: #3b82f6; text-transform: uppercase; margin-bottom: 4px;">Penyewa Saat Ini</span>
                        <div style="font-size: 18px; font-weight: 800; color: #1e293b;">{{ $room->user->name ?? 'Nama Tidak Diketahui' }}</div>
                        <div style="font-size: 14px; color: #64748b; font-weight: 500; margin-top: 2px;">📞 {{ $room->user->phone ?? '-' }}</div>
                    </div>
                </div>
                <a href="#" style="background: white; color: #3b82f6; text-decoration: none; padding: 10px 20px; border-radius: 12px; font-weight: 700; font-size: 13px; border: 1px solid #dbeafe; transition: 0.2s;" onmouseover="this.style.background='#3b82f6'; this.style.color='white'">
                    Lihat Profil
                </a>
            </div>
            @endif

            {{-- Fasilitas --}}
            <div style="background: white; border: 1px solid #f1f5f9; border-radius: 20px; padding: 30px;">
                <h3 style="font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 20px 0; display: flex; align-items: center; gap: 10px;">
                    Fasilitas Unit
                </h3>
                <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                    @php
                        $facilitiesArray = [];
                        if ($room->facilities) {
                            $facilitiesArray = is_array($room->facilities) ? $room->facilities : (json_decode($room->facilities, true) ?: []);
                        }
                    @endphp

                    @forelse($facilitiesArray as $facilityId)
                        @php $facility = \App\Models\Facility::find($facilityId); @endphp
                        @if($facility)
                            <span style="background: #f8fafc; color: #475569; border: 1.5px solid #f1f5f9; padding: 8px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                                <span style="color: #10b981;">✔</span> {{ $facility->name }}
                            </span>
                        @endif
                    @empty
                        <div style="text-align: center; width: 100%; padding: 20px; border: 2px dashed #f1f5f9; border-radius: 15px; color: #94a3b8; font-size: 14px;">
                            Tidak ada fasilitas khusus yang ditambahkan.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Deskripsi --}}
            @if($room->description)
            <div style="background: white; border: 1px solid #f1f5f9; border-radius: 20px; padding: 30px;">
                <h3 style="font-size: 15px; font-weight: 800; color: #1e293b; margin: 0 0 15px 0;">Catatan & Deskripsi</h3>
                <p style="font-size: 15px; color: #64748b; line-height: 1.7; margin: 0;">
                    {{ $room->description }}
                </p>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection