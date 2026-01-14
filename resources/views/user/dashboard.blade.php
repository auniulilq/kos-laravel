@extends('layouts.app')

@section('title', 'Dashboard Penyewa')

@section('content')
<style>
    .stat-card {
        background: white; 
        padding: 25px; 
        border-radius: 20px; 
        border: 1px solid #e2e8f0; 
        transition: transform 0.2s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .stat-card:hover { transform: translateY(-5px); }
    .status-badge { padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; color: white; text-transform: capitalize; }
</style>

<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Breadcrumb --}}
        @include('components.breadcrumb', ['breadcrumbs' => [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')]
        ]])
        
        {{-- Header Welcome --}}
        <div style="background: white; border-radius: 24px; padding: 35px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0;">
                    Selamat Datang, {{ $user->name }}! 👋
                </h1>
                <p style="color: #64748b; margin-top: 5px; font-size: 15px;">
                    {{ $room ? 'Pantau tagihan dan layanan kamar Anda di sini.' : 'Pantau status pengajuan kamar Anda.' }}
                </p>
            </div>
        </div>

        @if($room)
        {{-- Statistics Cards (Top Row) --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
            
            {{-- Pending Payments --}}
            <div class="stat-card" style="border-left: 6px solid #ef4444;">
                <div>
                    <span style="color: #64748b; font-size: 14px; font-weight: 600;">Tagihan Tertunda</span>
                    <div style="display: flex; align-items: baseline; gap: 10px; margin-top: 10px;">
                        <span style="font-size: 32px; font-weight: 800; color: #1e293b;">{{ $stats['pending_payments'] }}</span>
                        <span style="font-size: 13px; color: #ef4444; font-weight: 600;">Invoice</span>
                    </div>
                </div>
                @if($stats['pending_payments'] > 0)
                    <div style="font-size: 11px; color: #64748b; margin-top: 10px; padding-top: 10px; border-top: 1px solid #f1f5f9;">
                        @php
                            $types = \App\Models\Payment::where('user_id', auth()->id())
                                     ->where('status', 'pending')
                                     ->select('type', \DB::raw('count(*) as total'))
                                     ->groupBy('type')->get();
                        @endphp
                        @foreach($types as $t)
                            <span style="display: block;">• {{ $t->total }} {{ $t->type == 'room' ? 'Sewa Kamar' : 'Layanan' }}</span>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Verified Payments --}}
<div class="stat-card" style="border-left: 6px solid #10b981;">
    <span style="color: #64748b; font-size: 14px; font-weight: 600;">Transaksi Lunas</span>
    <!-- <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;"> {{-- align-items: center agar sejajar tengah --}} -->
        <span style="font-size: 32px; font-weight: 800; color: #1e293b; line-height: 1;">{{ $stats['verified_payments'] }}</span>
        <span style="font-size: 13px; color: #10b981; font-weight: 600;">Berhasil</span>
    <!-- </div> -->
</div>

            {{-- Pending Services --}}
<div class="stat-card" style="border-left: 6px solid #3b82f6;">
    <span style="color: #64748b; font-size: 14px; font-weight: 600;">Layanan Aktif</span>
    <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;"> {{-- align-items: center agar sejajar tengah --}}
        <span style="font-size: 32px; font-weight: 800; color: #1e293b; line-height: 1;">{{ $stats['pending_services'] }}</span>
        <span style="font-size: 13px; color: #3b82f6; font-weight: 600;">Proses</span>
    </div>
</div>

            {{-- Booking Aktif --}}
            <div class="stat-card" style="border-left: 6px solid #8b5cf6;">
                <span style="color: #64748b; font-size: 14px; font-weight: 600;">No. Kamar</span>
                <div style="display: flex; align-items: baseline; gap: 10px; margin-top: 10px;">
                    <span style="font-size: 28px; font-weight: 800; color: #1e293b;">{{ $active_booking->room->room_number ?? '-' }}</span>
                </div>
                <p style="margin: 12px 0 0 0; font-size: 12px; color: #64748b; border-top: 1px solid #f1f5f9; padding-top: 10px;">Status: Aktif</p>
            </div>
        </div>

        {{-- Grid Lower --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; margin-bottom: 30px;">
            
            {{-- Upcoming Payments --}}
            <div style="background: white; border-radius: 24px; padding: 25px; border: 1px solid #e2e8f0;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #1e293b;">Pembayaran Mendatang</h3>
                    <a href="{{ route('user.payments.index') }}" style="color: #3b82f6; font-size: 13px; font-weight: 600; text-decoration: none;">Semua →</a>
                </div>
                @forelse($upcoming_payments as $payment)
                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 10px;">
                        <div>
                            <p style="margin: 0; font-size: 14px; font-weight: 700;">{{ $payment['description'] }}</p>
                            <p style="margin: 4px 0 0 0; font-size: 12px; color: #64748b;">Tempo: {{ $payment['due_date'] }}</p>
                        </div>
                        <div style="text-align: right;">
                            <p style="margin: 0; font-size: 14px; font-weight: 800;">{{ $payment['amount'] }}</p>
                            <span style="color: #dc2626; font-size: 11px; font-weight: 600;">Pending</span>
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; padding: 30px; background: #f8fafc; border-radius: 12px; color: #64748b; border: 1px dashed #e2e8f0;">
                        <p style="font-size: 14px; margin: 0;">Semua tagihan lunas!</p>
                    </div>
                @endforelse
            </div>

            {{-- Service Status (Sekarang sama dengan desain Tagihan Tertunda) --}}
            <div class="stat-card" style="border-left: 6px solid #3b82f6; min-height: 200px;">
                <div>
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px;">
                        <span style="color: #64748b; font-size: 14px; font-weight: 600;">Layanan Terbaru</span>
                        @if($latest_service_status)
                            <span class="status-badge" style="background: {{ $latest_service_status['status_color'] }}">
                                {{ $latest_service_status['status'] }}
                            </span>
                        @endif
                    </div>

                    @if($latest_service_status)
                        <div style="margin-top: 10px;">
                            <h4 style="margin: 0; font-size: 18px; font-weight: 800; color: #1e293b;">{{ $latest_service_status['title'] }}</h4>
                            <p style="margin: 8px 0 0 0; font-size: 13px; color: #64748b; line-height: 1.5;">
                                {{ \Illuminate\Support\Str::limit($latest_service_status['description'], 100) }}
                            </p>
                        </div>
                    @else
                        <div style="text-align: center; padding: 20px 0;">
                            <p style="color: #94a3b8; font-size: 13px; font-style: italic;">Belum ada pengajuan layanan.</p>
                        </div>
                    @endif
                </div>

                <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                    <a href="{{ route('user.services.index') }}" style="color: #3b82f6; font-size: 13px; font-weight: 700; text-decoration: none;">Riwayat</a>
                    <a href="{{ route('user.services.create') }}" style="background: #3b82f6; color: white; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; text-decoration: none;">
                        + Ajukan Baru
                    </a>
                </div>
            </div>
        </div>

        {{-- Table Recent --}}
        <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden;">
            <div style="padding: 25px 30px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between;">
                <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;">Pembayaran Terakhir</h2>
                <a href="{{ route('user.payments.index') }}" style="font-size: 13px; color: #3b82f6; text-decoration: none;">Lihat Semua</a>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; text-align: left;">
                            <th style="padding: 15px 30px; font-size: 12px; color: #64748b;">INVOICE</th>
                            <th style="padding: 15px 30px; font-size: 12px; color: #64748b;">PERIODE</th>
                            <th style="padding: 15px 30px; font-size: 12px; color: #64748b;">TOTAL</th>
                            <th style="padding: 15px 30px; font-size: 12px; color: #64748b;">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recent_payments as $payment)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 20px 30px; font-weight: 700;">#{{ $payment->invoice_number }}</td>
                            <td style="padding: 20px 30px;">{{ \Carbon\Carbon::parse($payment->month_year)->format('F Y') }}</td>
                            <td style="padding: 20px 30px; font-weight: 600;">{{ $payment->formatted_amount }}</td>
                            <td style="padding: 20px 30px;">{!! $payment->status_badge !!}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="padding: 40px; text-align: center; color: #94a3b8;">Belum ada riwayat transaksi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @else
        {{-- Empty State --}}
        <div style="background: white; border-radius: 24px; padding: 60px; text-align: center; border: 2px dashed #e2e8f0;">
            <h2 style="font-size: 22px; font-weight: 800;">Belum Ada Kamar Aktif</h2>
            <p style="color: #64748b; margin-bottom: 20px;">Silakan hubungi admin untuk aktivasi kamar Anda.</p>
            <a href="{{ route('user.rooms.index') }}" style="background: #1e293b; color: white; padding: 12px 30px; border-radius: 12px; text-decoration: none; font-weight: 700;">Cari Kamar</a>
        </div>
        @endif
    </div>
</div>
@endsection
