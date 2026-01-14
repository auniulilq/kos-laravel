@extends('layouts.app')

@section('title', 'Detail Pembayaran ' . $payment->invoice_number)

@section('content')
<div style="max-width: 1100px; margin: 0 auto; padding: 20px; font-family: 'Inter', sans-serif;">
    
    {{-- Header & Action Buttons --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <a href="{{ route('admin.payments.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 600;">
                ← Kembali ke Daftar
            </a>
            <h1 style="font-size: 26px; font-weight: 800; color: #1e293b; margin: 8px 0 0 0;">
                Invoice #{{ $payment->invoice_number }}
            </h1>
        </div>
        <div style="display: flex; gap: 12px;">
            <a href="{{ route('admin.payments.print', $payment) }}" 
               style="text-decoration: none; background: white; border: 1px solid #e2e8f0; color: #475569; padding: 10px 20px; border-radius: 10px; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px;">
                <span>🖨️</span> Cetak PDF
            </a>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 30px; align-items: start;">
        
        {{-- KOLOM KIRI: INFO PEMBAYARAN --}}
        <div>
            <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;">
                <div style="padding: 20px 25px; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                    <h2 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">Rincian Transaksi</h2>
                </div>
                
                <div style="padding: 25px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 15px;">
                        <tr>
                            <td style="padding: 12px 0; color: #64748b; width: 40%;">Penyewa</td>
                            <td style="padding: 12px 0; color: #1e293b; font-weight: 600;">{{ $payment->user->name }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0; color: #64748b;">Unit Kamar</td>
                            <td style="padding: 12px 0;">
                                <span style="background: #eff6ff; color: #1e40af; padding: 4px 10px; border-radius: 6px; font-weight: 700; font-size: 13px;">
                                    {{ $payment->room->room_number }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0; color: #64748b;">Periode Sewa</td>
                            <td style="padding: 12px 0; color: #1e293b;">{{ \Carbon\Carbon::parse($payment->month_year)->format('F Y') }}</td>
                        </tr>
                        <tr style="border-top: 1px dashed #e2e8f0; border-bottom: 1px dashed #e2e8f0;">
                            <td style="padding: 20px 0; color: #64748b;">Total Tagihan</td>
                            <td style="padding: 20px 0; color: #3b82f6; font-size: 24px; font-weight: 800;">{{ $payment->formatted_amount }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 12px 0; color: #64748b;">Status Saat Ini</td>
                            <td style="padding: 12px 0;">{!! $payment->status_badge !!}</td>
                        </tr>
                        
                        @if($payment->paid_at)
                        <tr>
                            <td style="padding: 12px 0; color: #64748b;">Waktu Bayar</td>
                            <td style="padding: 12px 0; color: #1e293b;">{{ $payment->paid_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @endif

                        @if($payment->verified_at)
                        <tr style="background: #f0fdf4; border-radius: 8px;">
                            <td style="padding: 12px 10px; color: #15803d; font-size: 13px;">Diverifikasi Oleh</td>
                            <td style="padding: 12px 10px; color: #15803d; font-size: 13px; font-weight: 600;">
                                {{ $payment->verifier->name }} <br>
                                <small style="font-weight: 400;">pada {{ $payment->verified_at->format('d M Y, H:i') }}</small>
                            </td>
                        </tr>
                        @endif
                    </table>

                    {{-- Catatan Jika Ada --}}
                    @if($payment->notes)
                    <div style="margin-top: 25px; padding: 15px; border-radius: 12px; background: #fff1f2; border: 1px solid #ffe4e6;">
                        <span style="display: block; font-size: 11px; font-weight: 800; color: #e11d48; text-transform: uppercase; margin-bottom: 5px;">Alasan Penolakan / Catatan:</span>
                        <p style="margin: 0; font-size: 14px; color: #be123c;">{{ $payment->notes }}</p>
                    </div>
                    @endif

                    {{-- WA Reminder --}}
                    @php $canSendReminder = in_array($payment->status, ['pending','rejected']); @endphp
                    @if($canSendReminder)
                        <div style="margin-top: 25px;">
                            @if($payment->user && ($payment->user->whatsapp_opt_in ?? false))
                                <form action="{{ route('admin.payments.sendWaReminder', $payment) }}" method="POST">
                                    @csrf
                                    <button type="submit" style="width: 100%; background: #25d366; color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;">
                                        <span>📲</span> Kirim Pengingat WhatsApp
                                    </button>
                                </form>
                            @else
                                <div style="text-align: center; padding: 12px; background: #f8fafc; border-radius: 10px; font-size: 12px; color: #94a3b8; border: 1px solid #e2e8f0;">
                                    WhatsApp tidak diaktifkan oleh penyewa.
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: BUKTI & VERIFIKASI --}}
        <div>
            {{-- Box Bukti --}}
            <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden; margin-bottom: 25px;">
                <div style="padding: 20px 25px; border-bottom: 1px solid #f1f5f9; background: #f8fafc; display: flex; justify-content: space-between; align-items: center;">
                    <h2 style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">Bukti Transfer</h2>
                    @if($payment->proof_image)
                        <a href="{{ asset('storage/' . $payment->proof_image) }}" download style="text-decoration: none; font-size: 12px; font-weight: 700; color: #3b82f6;">Unduh Dokumen</a>
                    @endif
                </div>
                
                <div style="padding: 20px;">
                    @if($payment->proof_image)
                        <div style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; background: #f1f5f9;">
                            <img src="{{ asset('storage/' . $payment->proof_image) }}" style="width: 100%; display: block; cursor: zoom-in;" onclick="window.open(this.src)">
                        </div>
                        <p style="text-align: center; color: #94a3b8; font-size: 12px; margin-top: 10px;">Klik gambar untuk memperbesar</p>
                    @else
                        <div style="padding: 60px 20px; text-align: center; color: #cbd5e1;">
                            <span style="font-size: 40px; display: block; margin-bottom: 10px;">📑</span>
                            <span style="font-weight: 600;">Belum ada bukti diunggah</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Box Verifikasi (Hanya muncul jika status paid) --}}
            @if($payment->status === 'paid')
            <div style="background: #1e293b; border-radius: 16px; padding: 25px; color: white;">
                <h3 style="font-size: 16px; font-weight: 700; margin: 0 0 20px 0;">Konfirmasi Verifikasi</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <form action="{{ route('admin.payments.verify', $payment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" style="width: 100%; background: #10b981; color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer;" onclick="return confirm('Verifikasi pembayaran ini?')">
                            Setujui
                        </button>
                    </form>
                    <button onclick="document.getElementById('rejectArea').style.display='block'; this.style.display='none'" style="width: 100%; background: #ef4444; color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer;">
                        Tolak
                    </button>
                </div>

                <form id="rejectArea" action="{{ route('admin.payments.reject', $payment) }}" method="POST" style="display: none; margin-top: 20px; border-top: 1px solid #334155; padding-top: 20px;">
                    @csrf
                    @method('PATCH')
                    <label style="display: block; font-size: 12px; font-weight: 600; color: #94a3b8; margin-bottom: 8px;">ALASAN PENOLAKAN</label>
                    <textarea name="notes" required placeholder="Contoh: Bukti transfer tidak terbaca atau nominal tidak sesuai..." 
                              style="width: 100%; padding: 12px; border-radius: 8px; border: none; background: #334155; color: white; font-size: 14px; margin-bottom: 15px; resize: vertical;"></textarea>
                    <button type="submit" style="width: 100%; background: #ef4444; color: white; border: none; padding: 12px; border-radius: 10px; font-weight: 700; cursor: pointer;">
                        Kirim Penolakan
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Styling badge agar sesuai dengan model-model sebelumnya */
    .badge { padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
    .badge-success { background: #dcfce7; color: #15803d; }
    .badge-warning { background: #fff7ed; color: #9a3412; }
    .badge-danger { background: #fee2e2; color: #b91c1c; }
</style>
@endsection