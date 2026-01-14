@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div style="max-width: 1240px; margin: 0 auto; padding: 20px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    <x-breadcrumb :breadcrumbs="[['title' => 'Dashboard', 'url' => route('admin.dashboard')]]" />
    
    {{-- HEADER SECTION --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;">
        <div>
            <h1 style="font-size: 30px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">Dashboard Admin</h1>
            <p style="color: #64748b; margin: 8px 0 0 0; font-size: 15px;">Pantau performa operasional dan keuangan kos Anda.</p>
        </div>

        <div style="display: flex; align-items: center; gap: 20px;">
            {{-- Bagian Notifikasi --}}
            {{-- Ganti @include tadi dengan kode di bawah ini --}}
<div style="position: relative; display: inline-block;">
    <button onclick="toggleNotifications()" style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 16px; cursor: pointer; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); position: relative;">
        <span style="font-size: 20px;">🔔</span>
        @if($unread_notifications > 0)
            <span class="notif-pulse"></span>
            <span style="background: #ef4444; color: white; border-radius: 50%; width: 20px; height: 20px; font-size: 11px; display: flex; align-items: center; justify-content: center; font-weight: 700; position: absolute; top: -8px; right: -8px; border: 2px solid white;">
                {{ $unread_notifications }}
            </span>
        @endif
    </button>

    <div id="notificationsPanel" style="display: none; position: absolute; right: 0; top: 100%; margin-top: 10px; background: white; border: 1px solid #e2e8f0; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 350px; max-height: 400px; overflow-y: auto; z-index: 1000;">
        <div style="padding: 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #1e293b;">Notifikasi</h3>
            <span style="font-size: 12px; color: #3b82f6; font-weight: 600;">{{ $unread_notifications }} Baru</span>
        </div>
        <div style="padding: 10px 0;">
            @forelse($recent_user_activities as $activity)
                <div style="padding: 15px 20px; border-bottom: 1px solid #f8fafc; cursor: pointer;" onclick="window.location='{{ $activity['url'] }}'">
                    <p style="margin: 0; font-size: 13px; color: #334155; font-weight: 500;">{{ $activity['message'] }}</p>
                    <p style="margin: 4px 0 0 0; font-size: 11px; color: #94a3b8;">{{ $activity['time']->diffForHumans() }}</p>
                </div>
            @empty
                <div style="padding: 30px; text-align: center; color: #94a3b8;">Tidak ada notifikasi baru</div>
            @endforelse
        </div>
    </div>
</div>{{-- Disarankan dipindah ke file terpisah --}}
        </div>
    </div>

    {{-- STATISTIC CARDS --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 35px;">
        @php
            $cards = [
                ['label' => 'Total Kamar', 'value' => $stats['total_rooms'], 'color' => '#64748b', 'icon' => '🏠'],
                ['label' => 'Terisi', 'value' => $stats['occupied_rooms'], 'color' => '#3b82f6', 'icon' => '👤'],
                ['label' => 'Kosong', 'value' => $stats['empty_rooms'], 'color' => '#10b981', 'icon' => '✨'],
                ['label' => 'Perbaikan', 'value' => $stats['maintenance_rooms'], 'color' => '#ef4444', 'icon' => '🛠️'],
            ];
        @endphp

        @foreach($cards as $card)
        <div style="background: white; padding: 24px; border-radius: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; position: relative; overflow: hidden;">
            <div style="color: {{ $card['color'] }}; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">{{ $card['label'] }}</div>
            <div style="font-size: 36px; font-weight: 800; color: #1e293b; margin-top: 8px;">{{ $card['value'] }}</div>
            <div style="position: absolute; right: 15px; bottom: 10px; font-size: 40px; opacity: 0.1;">{{ $card['icon'] }}</div>
        </div>
        @endforeach
    </div>

    {{-- FINANCIAL & INVOICE STATUS --}}
    <div style="display: grid; grid-template-columns: 1.6fr 1fr; gap: 30px;">
        {{-- Income Card --}}
        <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #f1f5f9;">
            <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <span style="background: #e0f2fe; padding: 8px; border-radius: 10px;">📊</span> Operasional & Finansial
            </h2>
            
            <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); padding: 30px; border-radius: 20px; color: white; margin-bottom: 30px;">
                <div style="color: rgba(255,255,255,0.7); font-size: 13px; font-weight: 600; text-transform: uppercase; margin-bottom: 10px;">Estimasi Pendapatan Bulan Ini</div>
                <div style="font-size: 34px; font-weight: 800;">Rp {{ number_format($stats['monthly_income'], 0, ',', '.') }}</div>
            </div>

            @php $occPercent = $stats['total_rooms'] > 0 ? round(($stats['occupied_rooms'] / $stats['total_rooms']) * 100) : 0; @endphp
            <div>
                <div style="display: flex; justify-content: space-between; font-weight: 700; font-size: 14px; margin-bottom: 12px; color: #475569;">
                    <span>Tingkat Hunian (Occupancy)</span>
                    <span style="background: #f1f5f9; padding: 2px 10px; border-radius: 20px; color: #1e293b;">{{ $occPercent }}%</span>
                </div>
                <div style="background: #f1f5f9; height: 12px; border-radius: 10px; overflow: hidden;">
                    <div style="width: {{ $occPercent }}%; background: #3b82f6; height: 100%; transition: width 1s ease;"></div>
                </div>
            </div>
        </div>

        {{-- Invoice Status Card --}}
        <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #f1f5f9;">
            <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                <span style="background: #fef3c7; padding: 8px; border-radius: 10px;">📜</span> Status Invoice
            </h2>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @php
                    $statuses = [
                        ['label' => 'Terverifikasi', 'val' => $paymentSuccessData['data'][0], 'bg' => '#f0fdf4', 'text' => '#166534', 'border' => '#dcfce7'],
                        ['label' => 'Pending', 'val' => $paymentSuccessData['data'][1], 'bg' => '#fffbeb', 'text' => '#92400e', 'border' => '#fef3c7'],
                        ['label' => 'Gagal', 'val' => $paymentSuccessData['data'][2], 'bg' => '#fef2f2', 'text' => '#991b1b', 'border' => '#fee2e2'],
                    ];
                @endphp
                @foreach($statuses as $status)
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: {{ $status['bg'] }}; border-radius: 12px; border: 1px solid {{ $status['border'] }};">
                    <span style="color: {{ $status['text'] }}; font-weight: 700; font-size: 14px;">{{ $status['label'] }}</span>
                    <span style="font-size: 20px; font-weight: 900; color: {{ $status['text'] }};">{{ $status['val'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- RECENT TRANSACTIONS TABLE --}}
    <div style="background: white; margin-top: 35px; border-radius: 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); border: 1px solid #f1f5f9; overflow: hidden;">
        <div style="padding: 25px 30px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;">Transaksi Terkini</h2>
            <a href="{{ route('admin.payments.index') }}" style="text-decoration: none; color: #3b82f6; font-weight: 700; font-size: 14px; background: #eff6ff; padding: 8px 16px; border-radius: 10px;">Lihat Semua</a>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                        <th style="padding: 18px 30px; text-align: left; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800;">No. Invoice</th>
                        <th style="padding: 18px 30px; text-align: left; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800;">Penyewa</th>
                        <th style="padding: 18px 30px; text-align: left; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800;">Status</th>
                        <th style="padding: 18px 30px; text-align: left; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800;">Jumlah</th>
                        <th style="padding: 18px 30px; text-align: center; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent_payments as $payment)
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td style="padding: 20px 30px; font-weight: 700; color: #334155;">#{{ $payment->invoice_number }}</td>
                        <td style="padding: 20px 30px;">
                            <div style="font-weight: 600; color: #1e293b;">{{ $payment->user->name }}</div>
                            <div style="font-size: 12px; color: #94a3b8;">Kamar: {{ $payment->room->room_number ?? '-' }}</div>
                        </td>
                        <td style="padding: 20px 30px;">
                            @php
                                $isLunas = $payment->payment_status == 'lunas';
                                $bg = $isLunas ? '#dcfce7' : '#fef3c7';
                                $txt = $isLunas ? '#166534' : '#92400e';
                            @endphp
                            <span style="background: {{ $bg }}; color: {{ $txt }}; padding: 6px 14px; border-radius: 12px; font-size: 11px; font-weight: 800; text-transform: uppercase;">
                                {{ $isLunas ? 'Lunas' : 'Pending' }}
                            </span>
                        </td>
                        <td style="padding: 20px 30px; font-weight: 800; color: #1e293b;">{{ $payment->formatted_amount }}</td>
                        <td style="padding: 20px 30px; text-align: center;">
                            <a href="{{ route('admin.payments.show', $payment) }}" style="background: #1e293b; color: white; text-decoration: none; padding: 10px 20px; border-radius: 12px; font-size: 12px; font-weight: 700;">DETAIL</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- SCRIPT & STYLES KHUSUS NOTIFIKASI --}}
<style>
    @keyframes pulse-red {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .notif-pulse {
        position: absolute; top: 2px; right: 2px; width: 10px; height: 10px;
        background: #ef4444; border-radius: 50%; border: 2px solid white;
        animation: pulse-red 2s infinite;
    }
</style>

<script>
    function toggleNotifications() {
        const panel = document.getElementById('notificationsPanel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    }

    document.addEventListener('click', function(event) {
        const panel = document.getElementById('notificationsPanel');
        const button = event.target.closest('button');
        if (!button && panel && panel.style.display === 'block') {
            panel.style.display = 'none';
        }
    });
</script>
@endsection