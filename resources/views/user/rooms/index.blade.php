@extends('layouts.app')

@section('title', 'Daftar Kamar Tersedia')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Header Section --}}
        <div style="margin-bottom: 40px; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h1 style="font-size: 32px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Daftar Kamar</h1>
                <p style="color: #64748b; font-size: 16px;">Pilih kamar terbaik yang sesuai dengan kebutuhan Anda.</p>
            </div>
            <div style="font-size: 14px; color: #94a3b8;">
                Home / <span style="color: #1e293b; font-weight: 600;">Kamar</span>
            </div>
        </div>

        @if($rooms->count() > 0)
            {{-- Room Grid --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px;">
                @foreach($rooms as $room)
                    <div class="room-card" style="background: white; border-radius: 24px; overflow: hidden; border: 1px solid #e2e8f0; transition: all 0.3s ease; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                        {{-- Image Header --}}
                        <div style="position: relative; height: 220px; overflow: hidden;">
                            <img src="{{ $room->image ? asset('storage/' . $room->image) : 'https://picsum.photos/seed/room-'.$room->id.'/600/400' }}" 
                                 alt="Kamar {{ $room->room_number }}" 
                                 style="width: 100%; height: 100%; object-fit: cover;">
                            
                            <div style="position: absolute; top: 15px; left: 15px;">
                                <span style="background: rgba(255,255,255,0.9); backdrop-filter: blur(4px); padding: 6px 12px; border-radius: 10px; font-weight: 700; font-size: 11px; color: #1e293b; text-transform: uppercase;">
                                    {{ $room->category->name ?? 'Standard' }}
                                </span>
                            </div>

                            @if($room->user_id === auth()->id())
                                <div style="position: absolute; top: 15px; right: 15px;">
                                    <span style="background: #2563eb; color: white; padding: 6px 12px; border-radius: 10px; font-weight: 700; font-size: 11px; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.3);">
                                        KAMAR SAYA
                                    </span>
                                </div>
                            @endif
                        </div>

                        {{-- Card Body --}}
                        <div style="padding: 24px;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 16px;">
                                <div>
                                    <h3 style="font-size: 20px; font-weight: 800; color: #1e293b; margin-bottom: 4px;">Kamar {{ $room->room_number }}</h3>
                                    <div style="display: flex; align-items: center; gap: 8px; color: #64748b; font-size: 14px;">
                                        <span>📐 {{ $room->area ?? '3x4' }} m²</span>
                                        <span>•</span>
                                        <span>👥 Max {{ $room->capacity ?? '2' }} Org</span>
                                    </div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="color: #2563eb; font-weight: 800; font-size: 18px;">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                                    <div style="color: #94a3b8; font-size: 12px;">/ bulan</div>
                                </div>
                            </div>

                            <div style="margin-bottom: 24px;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    @php
                                        $statusColor = $room->status === 'occupied' ? '#ef4444' : ($room->status === 'empty' ? '#10b981' : '#f59e0b');
                                        $statusBg = $room->status === 'occupied' ? '#fee2e2' : ($room->status === 'empty' ? '#d1fae5' : '#fef3c7');
                                    @endphp
                                    <span style="display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: {{ $statusColor }};"></span>
                                    <span style="font-size: 13px; font-weight: 600; color: {{ $statusColor }}; text-transform: capitalize;">
                                        {{ $room->status === 'empty' ? 'Tersedia Sekarang' : 'Sudah Terisi' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Action --}}
                            <div style="display: flex; gap: 10px;">
                                <a href="{{ route('user.rooms.show', $room) }}" 
                                   style="flex: 1; text-align: center; text-decoration: none; background: #f1f5f9; color: #475569; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 14px; transition: 0.2s;"
                                   onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                                    Lihat Detail
                                </a>
                                
                                @if($room->status === 'empty')
                                    <a href="{{ route('user.rooms.show', $room) }}" 
                                       style="flex: 1.5; text-align: center; text-decoration: none; background: #2563eb; color: white; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 14px; transition: 0.2s; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);"
                                       onmouseover="this.style.background='#1d4ed8'; this.style.transform='translateY(-2px)'" 
                                       onmouseout="this.style.background='#2563eb'; this.style.transform='translateY(0)'">
                                        Booking Sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div style="margin-top: 50px; display: flex; justify-content: center;">
                {{ $rooms->links() }}
            </div>

        @else
            {{-- Empty State --}}
            <div style="background: white; border-radius: 24px; padding: 80px 20px; text-align: center; border: 1px solid #e2e8f0;">
                <div style="font-size: 64px; margin-bottom: 20px;">🛏️</div>
                <h3 style="font-size: 24px; font-weight: 800; color: #1e293b; margin-bottom: 10px;">Tidak Ada Kamar Tersedia</h3>
                <p style="color: #64748b; max-width: 400px; margin: 0 auto;">Maaf, saat ini belum ada data kamar yang dapat ditampilkan. Silakan hubungi admin atau kembali lagi nanti.</p>
                <a href="{{ route('user.dashboard') }}" style="display: inline-block; margin-top: 30px; color: #2563eb; font-weight: 700; text-decoration: none;"> Kembali ke Dashboard </a>
            </div>
        @endif
    </div>
</div>

<style>
    /* Hover effect untuk card */
    .room-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02) !important;
        border-color: #3b82f6 !important;
    }
    
    /* Customizing Laravel Pagination Style sedikit agar lebih clean */
    .pagination { margin-bottom: 0; }
    .page-item.active .page-link { background-color: #2563eb; border-color: #2563eb; border-radius: 8px; }
    .page-link { border-radius: 8px; margin: 0 3px; color: #475569; }
</style>
@endsection