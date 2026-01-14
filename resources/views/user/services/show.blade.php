@extends('layouts.app')

@section('title', 'Detail Layanan')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Navigation & Title --}}
        <div style="margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center;">
            <a href="{{ route('user.services.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Layanan
            </a>
            <span style="font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Request ID: #SR-{{ $serviceRequest->id }}</span>
        </div>

        <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 30px; align-items: start;">
            
            {{-- LEFT: Service Details --}}
            <div style="display: grid; gap: 25px;">
                <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <div style="padding: 25px 30px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
                        <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;">Informasi Layanan</h2>
                        {!! $serviceRequest->status_badge !!}
                    </div>
                    
                    <div style="padding: 30px;">
                        <table style="width: 100%; border-collapse: collapse;">
                            <tr>
                                <td style="padding: 12px 0; color: #64748b; font-size: 14px; width: 40%;">Jenis Layanan</td>
                                <td style="padding: 12px 0; color: #1e293b; font-weight: 700; font-size: 14px;">{{ $serviceRequest->service_type_name ?? strtoupper($serviceRequest->service_type) }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 0; color: #64748b; font-size: 14px;">Opsi / Spesifikasi</td>
                                <td style="padding: 12px 0; color: #1e293b; font-weight: 700; font-size: 14px;">{{ $serviceRequest->serviceOption->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 0; color: #64748b; font-size: 14px;">Jumlah / Kuantitas</td>
                                <td style="padding: 12px 0; color: #1e293b; font-weight: 700; font-size: 14px;">{{ $serviceRequest->quantity ?? 1 }} {{ $serviceRequest->serviceOption->unit_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 0; color: #64748b; font-size: 14px;">Unit Kamar</td>
                                <td style="padding: 12px 0; color: #1e293b; font-weight: 700; font-size: 14px;">{{ $serviceRequest->room->room_number ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 12px 0; color: #64748b; font-size: 14px;">Diajukan Pada</td>
                                <td style="padding: 12px 0; color: #1e293b; font-weight: 700; font-size: 14px;">{{ $serviceRequest->created_at->format('d M Y, H:i') }}</td>
                            </tr>
                            <tr style="border-top: 2px solid #f8fafc;">
                                <td style="padding: 20px 0 0 0; color: #1e293b; font-weight: 800; font-size: 16px;">Total Biaya</td>
                                <td style="padding: 20px 0 0 0; color: #2563eb; font-weight: 900; font-size: 20px;">{{ $serviceRequest->formatted_price ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div style="background: white; border-radius: 24px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <h2 style="font-size: 16px; font-weight: 800; color: #1e293b; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                        Deskripsi Permintaan
                    </h2>
                    <p style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0; background: #f8fafc; padding: 20px; border-radius: 16px; border: 1px solid #f1f5f9;">
                        {{ $serviceRequest->description }}
                    </p>
                </div>
            </div>

            {{-- RIGHT: Linked Payment --}}
            <div style="position: sticky; top: 40px;">
                <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
                    <div style="padding: 25px; background: #fcfdfe; border-bottom: 1px solid #f1f5f9;">
                        <h2 style="font-size: 16px; font-weight: 800; color: #1e293b; margin: 0;">Status Pembayaran</h2>
                    </div>
                    
                    <div style="padding: 25px;">
                        @if($serviceRequest->payment)
                            <div style="text-align: center; margin-bottom: 25px;">
                                <span style="display: block; font-size: 12px; color: #94a3b8; text-transform: uppercase; font-weight: 700; margin-bottom: 5px;">Nomor Invoice</span>
                                <span style="display: block; font-size: 18px; font-weight: 800; color: #1e293b;">{{ $serviceRequest->payment->invoice_number }}</span>
                            </div>

                            <div style="display: grid; gap: 15px; margin-bottom: 25px;">
                                <div style="display: flex; justify-content: space-between; font-size: 14px;">
                                    <span style="color: #64748b;">Jumlah Bayar</span>
                                    <span style="font-weight: 700; color: #1e293b;">{{ $serviceRequest->payment->formatted_amount }}</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 14px;">
                                    <span style="color: #64748b;">Status Tagihan</span>
                                    <span>{!! $serviceRequest->payment->status_badge !!}</span>
                                </div>
                            </div>

                            <a href="{{ route('user.payments.show', $serviceRequest->payment) }}" style="display: block; text-align: center; text-decoration: none; background: #1e293b; color: white; padding: 12px; border-radius: 12px; font-weight: 700; font-size: 14px; transition: 0.2s;" onmouseover="this.style.background='#000'" onmouseout="this.style.background='#1e293b'">
                                Detail Transaksi
                            </a>
                        @else
                            <div style="text-align: center; padding: 20px 0;">
                                <div style="font-size: 40px; margin-bottom: 15px;">⏳</div>
                                <p style="font-size: 14px; color: #64748b; font-weight: 500; line-height: 1.5;">
                                    Belum ada tagihan yang diterbitkan untuk layanan ini.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Help Note --}}
                <div style="margin-top: 20px; padding: 15px; border-radius: 16px; background: #eff6ff; border: 1px solid #dbeafe;">
                    <p style="font-size: 12px; color: #1e40af; margin: 0; line-height: 1.5;">
                        <strong>Informasi:</strong> Biaya layanan akan otomatis ditambahkan ke invoice bulanan Anda atau diterbitkan sebagai invoice terpisah tergantung kebijakan pengelola.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection