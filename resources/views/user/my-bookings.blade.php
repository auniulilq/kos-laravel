@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Page Header --}}
        <div style="margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h2 style="font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Pesanan Saya</h2>
                <p style="color: #64748b; margin-bottom: 0;">Kelola dan pantau status penyewaan kamar Anda di sini.</p>
            </div>
            <a href="{{ route('user.rooms.index') }}" 
               style="background: white; color: #2563eb; padding: 10px 20px; border-radius: 12px; font-weight: 600; text-decoration: none; font-size: 14px; border: 1px solid #e2e8f0; transition: 0.2s;"
               onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='white'">
               + Cari Kamar Lain
            </a>
        </div>

        @if($bookings->count() > 0)
            <div style="display: grid; gap: 20px;">
                @foreach($bookings as $booking)
                <div style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; padding: 24px; display: flex; align-items: center; justify-content: space-between; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);"
                     onmouseover="this.style.borderColor='#2563eb'; this.style.boxShadow='0 10px 15px -3px rgba(0,0,0,0.05)'" 
                     onmouseout="this.style.borderColor='#e2e8f0'; this.style.boxShadow='0 4px 6px -1px rgba(0,0,0,0.02)'">
                    
                    {{-- Info Kamar & Invoice --}}
                    <div style="display: flex; align-items: center; gap: 24px; flex: 2;">
                        <div style="width: 80px; height: 80px; background: #f1f5f9; border-radius: 16px; display: flex; flex-direction: column; align-items: center; justify-content: center; border: 1px solid #e2e8f0;">
                            <span style="font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Kamar</span>
                            <span style="font-size: 20px; font-weight: 900; color: #1e293b;">{{ $booking->room->room_number ?? '-' }}</span>
                        </div>
                        
                        <div>
                            <div style="font-size: 12px; font-weight: 700; color: #2563eb; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                                {{ $booking->invoice_number }}
                            </div>
                            <h4 style="font-size: 18px; font-weight: 700; color: #1e293b; margin-bottom: 4px;">
                                {{ $booking->room->category->name ?? 'Tipe Standar' }}
                            </h4>
                            <div style="display: flex; align-items: center; gap: 12px; color: #64748b; font-size: 13px;">
                                <span><i class="far fa-calendar-alt"></i> {{ $booking->start_date->format('d M Y') }}</span>
                                <span style="color: #cbd5e1;">•</span>
                                <span style="background: #f8fafc; padding: 2px 8px; border-radius: 6px; font-weight: 600;">{{ $booking->duration_type }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Harga & Status --}}
                    <div style="flex: 1; text-align: center;">
                        <div style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 4px;">
                            Rp{{ number_format($booking->total_price, 0, ',', '.') }}
                        </div>
                        <div style="display: inline-block;">
                            @if($booking->payment_status == 'lunas')
                                <span style="background: #dcfce7; color: #15803d; padding: 4px 12px; border-radius: 30px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 5px;">
                                    <span style="width: 6px; height: 6px; background: #15803d; border-radius: 50%;"></span> Lunas
                                </span>
                            @elseif($booking->status == 'active')
                                <span style="background: #eff6ff; color: #1d4ed8; padding: 4px 12px; border-radius: 30px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 5px;">
                                    <span style="width: 6px; height: 6px; background: #1d4ed8; border-radius: 50%;"></span> Aktif
                                </span>
                            @else
                                <span style="background: #fef3c7; color: #92400e; padding: 4px 12px; border-radius: 30px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 5px;">
                                    <span style="width: 6px; height: 6px; background: #92400e; border-radius: 50%;"></span> Menunggu Bayar
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Aksi --}}
                    <div style="flex: 1; display: flex; justify-content: flex-end; gap: 12px;">
                        <a href="{{ route('bookings.show', $booking->id) }}" 
                           style="background: #f1f5f9; color: #475569; padding: 10px 18px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 13px; transition: 0.2s;"
                           onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                           Detail
                        </a>
                        
                        @if($booking->payment_status != 'lunas')
                            <a href="{{ route('bookings.show', $booking->id) }}" 
                               style="background: #2563eb; color: white; padding: 10px 18px; border-radius: 12px; font-weight: 700; text-decoration: none; font-size: 13px; transition: 0.2s; box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);"
                               onmouseover="this.style.background='#1d4ed8'; this.style.transform='translateY(-1px)'" 
                               onmouseout="this.style.background='#2563eb'; this.style.transform='translateY(0)'">
                               Bayar Sekarang
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div style="margin-top: 32px; display: flex; justify-content: center;">
                {{ $bookings->links() }}
            </div>

        @else
            {{-- Empty State --}}
            <div style="background: white; border-radius: 24px; padding: 80px 40px; text-align: center; border: 1px solid #e2e8f0;">
                <div style="background: #f8fafc; width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 24px;">
                    <svg width="40" height="40" fill="none" stroke="#94a3b8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h3 style="font-size: 20px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Belum Ada Pesanan</h3>
                <p style="color: #64748b; margin-bottom: 32px; max-width: 400px; margin-left: auto; margin-right: auto;">
                    Anda belum memiliki riwayat pemesanan kamar. Jelajahi pilihan kamar terbaik kami dan mulai perjalanan Anda.
                </p>
                <a href="{{ route('user.rooms.index') }}" 
                   style="background: #2563eb; color: white; padding: 14px 32px; border-radius: 14px; font-weight: 700; text-decoration: none; display: inline-block; transition: 0.2s;"
                   onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                   Cari Kamar Sekarang
                </a>
            </div>
        @endif

    </div>
</div>
@endsection