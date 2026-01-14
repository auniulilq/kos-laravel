@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 40px auto; padding: 20px; font-family: 'Inter', sans-serif;">
    
    {{-- Main Invoice Card --}}
    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05);">
        
        {{-- Top Section: Status --}}
        <div style="padding: 30px; text-align: center; border-bottom: 1px dashed #e2e8f0; background: #f8fafc;">
            <div style="font-size: 14px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Nomor Invoice</div>
            <h2 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0;">#{{ $booking->id }}</h2>
            
            <div style="margin-top: 15px;">
                @if($booking->payment_status == 'unpaid')
                    <span style="background: #fff1f2; color: #e11d48; padding: 6px 16px; border-radius: 99px; font-size: 12px; font-weight: 800; border: 1px solid #fecdd3;">
                        🔴 MENUNGGU PEMBAYARAN
                    </span>
                @else
                    <span style="background: #f0fdf4; color: #16a34a; padding: 6px 16px; border-radius: 99px; font-size: 12px; font-weight: 800; border: 1px solid #dcfce7;">
                        ✅ PEMBAYARAN LUNAS
                    </span>
                @endif
            </div>
        </div>

        {{-- Detail Section --}}
        <div style="padding: 30px;">
            <div style="display: grid; gap: 20px;">
                
                {{-- Room Info --}}
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="display: block; font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Unit Kamar</span>
                        <span style="font-size: 16px; font-weight: 700; color: #1e293b;">Room {{ $booking->room_id }}</span>
                    </div>
                    <div style="text-align: right;">
                        <span style="display: block; font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase;">Durasi</span>
                        <span style="font-size: 16px; font-weight: 700; color: #1e293b;">{{ ucfirst($booking->duration_type) }}</span>
                    </div>
                </div>

                {{-- Dates --}}
                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <span style="display: block; font-size: 11px; color: #94a3b8; font-weight: 700;">CHECK-IN</span>
                        <span style="font-size: 14px; font-weight: 700; color: #334155;">{{ $booking->start_date->format('d M Y') }}</span>
                    </div>
                    <div style="color: #cbd5e1;">→</div>
                    <div style="text-align: right;">
                        <span style="display: block; font-size: 11px; color: #94a3b8; font-weight: 700;">CHECK-OUT</span>
                        <span style="font-size: 14px; font-weight: 700; color: #334155;">{{ $booking->end_date->format('d M Y') }}</span>
                    </div>
                </div>

                <hr style="border: none; border-top: 1px solid #f1f5f9; margin: 10px 0;">

                {{-- Pricing --}}
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <span style="font-size: 16px; font-weight: 700; color: #1e293b;">Total Tagihan</span>
                    <span style="font-size: 22px; font-weight: 900; color: #3b82f6;">Rp{{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>

                @if($booking->payment_status == 'unpaid')
                    @php
                        $paymentLink = isset($payment) && $payment
                            ? route('user.payments.show', $payment->id)
                            : route('user.my-bookings');
                        $bankName = env('BANK_NAME', 'BCA');
                        $bankAccountNumber = env('BANK_ACCOUNT_NUMBER', '1234567890');
                        $bankAccountName = env('BANK_ACCOUNT_NAME', 'PT Kawan Kost');
                        $transferAmount = isset($payment) && $payment ? $payment->amount : $booking->total_price;
                    @endphp
                    <div style="background: #f8fafc; border-radius: 16px; padding: 18px; border: 1px dashed #cbd5e1;">
                        <div style="font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 10px;">Instruksi Transfer Bank</div>
                        <ul style="font-size: 14px; color: #334155; margin: 0 0 12px 16px;">
                            <li>Nomor Invoice: <strong>{{ $booking->invoice_number }}</strong></li>
                            <li>Nominal Transfer: <strong>Rp {{ number_format($transferAmount, 0, ',', '.') }}</strong></li>
                            <li>Bank: <strong>{{ $bankName }}</strong></li>
                            <li>No. Rekening: <strong>{{ $bankAccountNumber }}</strong></li>
                            <li>Nama Rekening: <strong>{{ $bankAccountName }}</strong></li>
                        </ul>
                        <a href="{{ $paymentLink }}" style="display: inline-block; text-decoration: none; background: #2563eb; color: white; padding: 12px 16px; border-radius: 12px; font-weight: 800;">Upload Bukti Transfer</a>
                        <p style="font-size: 12px; color: #64748b; margin-top: 8px;">Setelah admin verifikasi, booking akan aktif dan kamar menjadi occupied.</p>
                    </div>
                @else
                    <div style="background: #10b981; color: white; padding: 18px; border-radius: 16px; text-align: center; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 10px;">
                        <span>SUDAH TERBAYAR</span>
                        <span style="background: rgba(255,255,255,0.2); width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">✓</span>
                    </div>
                    <a href="{{ route('user.my-bookings') }}" style="text-align: center; display: block; text-decoration: none; color: #64748b; font-size: 14px; font-weight: 600; margin-top: 10px;">Kembali ke Pesanan Saya</a>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection