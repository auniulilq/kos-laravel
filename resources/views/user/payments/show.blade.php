@extends('layouts.app')

@section('title', 'Detail Pembayaran ' . $payment->invoice_number)

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 900px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Navigation --}}
        <div style="margin-bottom: 24px;">
            <a href="{{ route('user.payments.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Riwayat
            </a>
        </div>

        <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 30px; align-items: start;">
            
            {{-- LEFT: Receipt Info --}}
            <div>
                <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
                    <div style="background: #f8fafc; padding: 30px; text-align: center; border-bottom: 1px dashed #e2e8f0;">
                        <div style="font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 5px;">Nomor Invoice</div>
                        <h1 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0;">#{{ $payment->invoice_number }}</h1>
                        
                        <div style="margin-top: 15px;">
                            @if($payment->status === 'pending')
                                <span style="background: #fff1f2; color: #e11d48; padding: 6px 16px; border-radius: 99px; font-size: 12px; font-weight: 800; border: 1px solid #fecdd3;">
                                    🔴 MENUNGGU PEMBAYARAN
                                </span>
                            @elseif($payment->status === 'success')
                                <span style="background: #f0fdf4; color: #16a34a; padding: 6px 16px; border-radius: 99px; font-size: 12px; font-weight: 800; border: 1px solid #dcfce7;">
                                    ✅ PEMBAYARAN LUNAS
                                </span>
                            @else
                                {!! $payment->status_badge !!}
                            @endif
                        </div>
                    </div>
                    
                    <div style="padding: 30px;">
                        <div style="display: grid; gap: 20px;">
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding-bottom: 15px;">
                                <span style="color: #64748b; font-size: 14px;">Unit Kamar</span>
                                <span style="font-weight: 700; color: #1e293b;">Room {{ $payment->room->room_number }}</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; border-bottom: 1px dashed #e2e8f0; padding-bottom: 15px;">
                                <span style="color: #64748b; font-size: 14px;">Periode Sewa</span>
                                <span style="font-weight: 700; color: #1e293b;">{{ \Carbon\Carbon::parse($payment->month_year)->format('F Y') }}</span>
                            </div>
                            <div style="padding-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                                <span style="color: #1e293b; font-size: 16px; font-weight: 700;">Total Tagihan</span>
                                <span style="font-size: 24px; font-weight: 900; color: #3b82f6;">{{ $payment->formatted_amount }}</span>
                            </div>
                        </div>

                        @if($payment->notes)
                        <div style="margin-top: 30px; padding: 20px; background: #fff1f2; border-radius: 16px; border: 1px solid #fee2e2;">
                            <span style="display: block; font-size: 12px; font-weight: 800; color: #b91c1c; text-transform: uppercase; margin-bottom: 8px;">Catatan Admin:</span>
                            <p style="font-size: 14px; color: #991b1b; margin: 0; line-height: 1.5;">{{ $payment->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- RIGHT: Payment Action --}}
            <div>
                @if($payment->status === 'pending')
                    <div style="background: white; border-radius: 24px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
                        <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 20px;">Selesaikan Pembayaran</h2>
                        
                        @if(!$payment->proof_image)
                            @php
                                $bankName = env('BANK_NAME', 'BCA');
                                $bankAccountNumber = env('BANK_ACCOUNT_NUMBER', '1234567890');
                                $bankAccountName = env('BANK_ACCOUNT_NAME', 'PT Kawan Kost');
                            @endphp
                            <div style="background: #f8fafc; border-radius: 16px; padding: 16px; border: 1px dashed #cbd5e1; margin-bottom: 16px;">
                                <div style="font-size: 14px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Instruksi Transfer Bank</div>
                                <ul style="font-size: 14px; color: #334155; margin: 0 0 0 18px;">
                                    <li>Nomor Invoice: <strong>{{ $payment->invoice_number }}</strong></li>
                                    <li>Nominal Transfer: <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></li>
                                    <li>Bank: <strong>{{ $bankName }}</strong></li>
                                    <li>No. Rekening: <strong>{{ $bankAccountNumber }}</strong></li>
                                    <li>Nama Rekening: <strong>{{ $bankAccountName }}</strong></li>
                                </ul>
                            </div>

                            {{-- Manual Upload Form --}}
                            @if($errors->has('proof_image'))
                                <p style="color: #ef4444; font-size: 12px; margin-bottom: 5px;">{{ $errors->first('proof_image') }}</p>
                            @endif
                            <form action="{{ route('user.payments.upload', $payment) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div style="margin-bottom: 15px;">
                                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Upload Bukti Transfer</label>
                                    <input type="file" name="proof_image" accept="image/*" required style="width: 100%; font-size: 13px; color: #64748b; border: 1px solid #e2e8f0; padding: 10px; border-radius: 10px;">
                                </div>
                                
                                <button type="submit" style="width: 100%; background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; padding: 14px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#f1f5f9'" onmouseout="this.style.background='#f8fafc'">
                                    Konfirmasi
                                </button>
                            </form>
                        @else
                            <div style="background: #f1f5f9; border-radius: 16px; padding: 20px; text-align: center; border: 1px dashed #cbd5e1;">
                                <p style="color: #64748b; font-size: 14px; margin: 0;">Bukti pembayaran telah diunggah & sedang diverifikasi.</p>
                            </div>
                        @endif
                    </div>
                @elseif($payment->status === 'success')
                    <div style="background: white; border-radius: 24px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); text-align: center;">
                        <div style="background: #10b981; color: white; padding: 18px; border-radius: 16px; font-weight: 800; display: flex; align-items: center; justify-content: center; gap: 10px;">
                            <span>SUDAH TERBAYAR</span>
                            <span style="background: rgba(255,255,255,0.2); width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px;">✓</span>
                        </div>
                        <a href="{{ route('user.payments.index') }}" style="display: block; text-decoration: none; color: #64748b; font-size: 14px; font-weight: 600; margin-top: 15px;">Kembali ke Riwayat</a>
                    </div>
                @endif

                {{-- Preview Bukti --}}
                @if($payment->proof_image)
                    <div style="background: white; border-radius: 24px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); margin-top: 20px;">
                        <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 20px;">Bukti Anda</h2>
                        <div style="border-radius: 16px; overflow: hidden; border: 1px solid #e2e8f0;">
                            <img src="{{ asset('storage/' . $payment->proof_image) }}" alt="Bukti" style="width: 100%; display: block;">
                        </div>
                    </div>
                @endif
            </div>
            
        </div>
    </div>
</div>

@endsection