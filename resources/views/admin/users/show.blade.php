@extends('layouts.app')

@section('title', 'Profil Penyewa')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 25px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    {{-- Top Header & Navigation --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
        <div>
            <a href="{{ route('admin.users.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px; margin-bottom: 10px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'">
                ← Kembali ke Daftar
            </a>
            <h1 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -1px;">Detail Profil Penyewa</h1>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.users.edit', $user) }}" style="text-decoration: none; background: white; color: #1e293b; padding: 12px 24px; border-radius: 14px; font-weight: 700; font-size: 14px; border: 1.5px solid #e2e8f0; transition: 0.2s; display: flex; align-items: center; gap: 8px;" onmouseover="this.style.background='#f8fafc'">
                ✏️ Edit Profil
            </a>
            {{-- Tombol aksi tambahan jika diperlukan --}}
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 380px 1fr; gap: 30px; align-items: start;">
        
        {{-- Kolom Kiri: Profil & Status Kamar --}}
        <div style="display: flex; flex-direction: column; gap: 25px;">
            
            {{-- Card Identitas --}}
            <div style="background: white; border-radius: 24px; border: 1px solid #f1f5f9; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
                <div style="background: linear-gradient(to bottom right, #f8fafc, #eff6ff); padding: 40px 30px; text-align: center; border-bottom: 1px solid #f1f5f9;">
                    <div style="width: 100px; height: 100px; background: #3b82f6; color: white; border-radius: 30px; display: flex; align-items: center; justify-content: center; font-size: 42px; font-weight: 800; margin: 0 auto 20px; box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.3); transform: rotate(-5deg);">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                    <h2 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0;">{{ $user->name }}</h2>
                    <div style="display: inline-block; margin-top: 10px; padding: 4px 12px; background: #dcfce7; color: #166534; border-radius: 20px; font-size: 12px; font-weight: 700;">
                        ID: #USR-{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                    </div>
                </div>
                <div style="padding: 30px;">
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Email Address</label>
                        <p style="font-size: 15px; color: #1e293b; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 8px;">
                            📧 {{ $user->email }}
                        </p>
                    </div>
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">WhatsApp / Phone</label>
                        <p style="font-size: 15px; color: #1e293b; font-weight: 600; margin: 0; display: flex; align-items: center; gap: 8px;">
                            📱 {{ $user->phone }}
                        </p>
                    </div>
                    <div style="margin-bottom: 25px;">
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 6px;">Origin Address</label>
                        <p style="font-size: 14px; color: #64748b; font-weight: 500; margin: 0; line-height: 1.6;">
                            📍 {{ $user->address }}
                        </p>
                    </div>
                    <hr style="border: 0; border-top: 1px solid #f1f5f9; margin-bottom: 20px;">
                    <p style="font-size: 12px; color: #94a3b8; text-align: center; margin: 0;">Bergabung sejak {{ $user->created_at->format('d F Y') }}</p>
                </div>
            </div>

            {{-- Card Kamar --}}
            @if($user->room)
            <div style="background: #1e293b; border-radius: 24px; padding: 30px; color: white; position: relative; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(30, 41, 59, 0.2);">
                <div style="position: absolute; right: -15px; top: -15px; font-size: 120px; opacity: 0.1; transform: rotate(15deg);">🚪</div>
                <h3 style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 20px; letter-spacing: 1.5px;">Current Unit</h3>
                
                <div style="display: flex; align-items: baseline; gap: 10px; margin-bottom: 5px;">
                    <span style="font-size: 48px; font-weight: 800; letter-spacing: -2px;">{{ $user->room->room_number }}</span>
                    <span style="font-size: 14px; color: #94a3b8; font-weight: 600;">{{ strtoupper($user->room->type) }}</span>
                </div>
                
                <div style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); padding: 15px; border-radius: 16px; margin-bottom: 25px; margin-top: 15px;">
                    <span style="font-size: 11px; display: block; color: #94a3b8; margin-bottom: 4px; text-transform: uppercase;">Monthly Rent</span>
                    <span style="font-size: 20px; font-weight: 700; color: #10b981;">{{ $user->room->formatted_price }}</span>
                </div>
                
                <a href="{{ route('admin.rooms.show', $user->room) }}" style="display: block; text-align: center; text-decoration: none; color: white; background: #3b82f6; padding: 14px; border-radius: 12px; font-size: 13px; font-weight: 700; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);" onmouseover="this.style.background='#2563eb'">
                    Buka Detail Kamar
                </a>
            </div>
            @else
            <div style="background: white; border: 2px dashed #e2e8f0; border-radius: 24px; padding: 40px 30px; text-align: center;">
                <div style="font-size: 40px; margin-bottom: 15px; filter: grayscale(1);">🚪</div>
                <h3 style="color: #1e293b; font-weight: 800; font-size: 16px; margin: 0;">Belum Menempati Kamar</h3>
                <p style="color: #94a3b8; font-size: 13px; margin-top: 8px; line-height: 1.5;">Penyewa ini terdaftar namun belum dialokasikan ke unit manapun.</p>
                <a href="{{ route('admin.users.edit', $user) }}" style="display: inline-block; margin-top: 15px; color: #3b82f6; text-decoration: none; font-weight: 700; font-size: 13px;">+ Tetapkan Kamar Sekarang</a>
            </div>
            @endif
        </div>

        {{-- Kolom Kanan: Riwayat Aktivitas --}}
        <div style="display: flex; flex-direction: column; gap: 30px;">
            
            {{-- Tabel Pembayaran --}}
            <div style="background: white; border-radius: 24px; border: 1px solid #f1f5f9; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                <div style="padding: 25px 30px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                    <div>
                        <h3 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;">Riwayat Pembayaran</h3>
                        <p style="margin: 0; font-size: 12px; color: #94a3b8;">5 Transaksi terakhir penyewa</p>
                    </div>
                    <a href="{{ route('admin.payments.index', ['user_id' => $user->id]) }}" style="text-decoration: none; font-size: 13px; font-weight: 700; color: #3b82f6; padding: 8px 16px; background: #eff6ff; border-radius: 10px;">Lihat Semua</a>
                </div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; background: white;">
                                <th style="padding: 15px 30px; font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Invoice</th>
                                <th style="padding: 15px 30px; font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Periode</th>
                                <th style="padding: 15px 30px; font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Jumlah</th>
                                <th style="padding: 15px 30px; font-size: 11px; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->payments->take(5) as $payment)
                            <tr style="border-top: 1px solid #f8fafc; transition: 0.2s;" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 18px 30px; font-size: 14px; font-weight: 700; color: #1e293b;">#{{ $payment->invoice_number }}</td>
                                <td style="padding: 18px 30px; font-size: 14px; color: #475569;">{{ \Carbon\Carbon::parse($payment->month_year)->format('M Y') }}</td>
                                <td style="padding: 18px 30px; font-size: 14px; font-weight: 700; color: #10b981;">{{ $payment->formatted_amount }}</td>
                                <td style="padding: 18px 30px; text-align: center;">{!! $payment->status_badge !!}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="padding: 60px 30px; text-align: center;">
                                    <div style="font-size: 32px; margin-bottom: 10px;">🧾</div>
                                    <p style="color: #94a3b8; font-size: 14px; margin: 0;">Belum ada riwayat transaksi ditemukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tabel Layanan --}}
            <div style="background: white; border-radius: 24px; border: 1px solid #f1f5f9; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                <div style="padding: 25px 30px; border-bottom: 1px solid #f1f5f9; background: #fafafa;">
                    <h3 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;">Layanan & Request</h3>
                    <p style="margin: 0; font-size: 12px; color: #94a3b8;">Permintaan tambahan dan servis kamar</p>
                </div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left;">
                                <th style="padding: 15px 30px; font-size: 11px; color: #94a3b8; text-transform: uppercase;">Jenis Layanan</th>
                                <th style="padding: 15px 30px; font-size: 11px; color: #94a3b8; text-transform: uppercase;">Biaya</th>
                                <th style="padding: 15px 30px; font-size: 11px; color: #94a3b8; text-transform: uppercase; text-align: center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user->serviceRequests->take(5) as $service)
                            <tr style="border-top: 1px solid #f8fafc;">
                                <td style="padding: 18px 30px; font-size: 14px; font-weight: 700; color: #1e293b;">{{ $service->service_type_name }}</td>
                                <td style="padding: 18px 30px; font-size: 14px; color: #475569;">{{ $service->formatted_price }}</td>
                                <td style="padding: 18px 30px; text-align: center;">{!! $service->status_badge !!}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="padding: 60px 30px; text-align: center;">
                                    <div style="font-size: 32px; margin-bottom: 10px;">🛠️</div>
                                    <p style="color: #94a3b8; font-size: 14px; margin: 0;">Tidak ada permintaan layanan aktif.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection