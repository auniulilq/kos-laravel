@extends('layouts.app')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Header Section --}}
        <div style="margin-bottom: 32px; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <h1 style="font-size: 26px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
                    Tagihan & Invoice
                </h1>
                <p style="color: #64748b; margin-top: 4px; font-size: 15px;">Pantau riwayat pembayaran bulanan dan unduh laporan transaksi Anda.</p>
            </div>
            
            {{-- Quick Stats --}}
            <div style="display: flex; gap: 12px;">
                <div style="background: white; padding: 10px 20px; border-radius: 14px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 10px;">
                    <span style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></span>
                    <span style="font-size: 13px; font-weight: 700; color: #475569;">{{ $invoices->where('status', 'overdue')->count() }} Terlambat</span>
                </div>
            </div>
        </div>

        {{-- Table Card --}}
        <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Periode Tagihan</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Jatuh Tempo</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; text-align: right;">Jumlah</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px; color: #475569;">
                        @forelse ($invoices as $invoice)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;" onmouseover="this.style.background='#fcfdfe'" onmouseout="this.style.background='transparent'">
                            {{-- Periode --}}
                            <td style="padding: 20px 24px;">
                                <div style="font-weight: 700; color: #1e293b;">{{ $invoice->period_month }}</div>
                                <div style="font-size: 12px; color: #94a3b8; margin-top: 2px;">Invoice #INV-{{ $invoice->id }}</div>
                            </td>
                            
                            {{-- Jatuh Tempo --}}
                            <td style="padding: 20px 24px; color: #64748b;">
                                {{ optional($invoice->due_date)->format('d M Y') ?? '-' }}
                            </td>
                            
                            {{-- Status Badge --}}
                            <td style="padding: 20px 24px;">
                                @php
                                    $statusStyles = match($invoice->status) {
                                        'draft' => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => 'Draft'],
                                        'issued' => ['bg' => '#eff6ff', 'color' => '#2563eb', 'label' => 'Dikirim'],
                                        'paid' => ['bg' => '#dcfce7', 'color' => '#15803d', 'label' => 'Lunas'],
                                        'overdue' => ['bg' => '#fee2e2', 'color' => '#b91c1c', 'label' => 'Terlambat'],
                                        default => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => ucfirst($invoice->status)]
                                    };
                                @endphp
                                <span style="background: {{ $statusStyles['bg'] }}; color: {{ $statusStyles['color'] }}; padding: 6px 12px; border-radius: 8px; font-size: 12px; font-weight: 700; display: inline-block;">
                                    {{ $statusStyles['label'] }}
                                </span>
                            </td>
                            
                            {{-- Total Amount --}}
                            <td style="padding: 20px 24px; text-align: right; font-weight: 800; color: #1e293b;">
                                Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}
                            </td>
                            
                            {{-- Actions --}}
                            <td style="padding: 20px 24px; text-align: center;">
                                <div style="display: flex; gap: 8px; justify-content: center;">
                                    <a href="{{ route('invoices.show', $invoice) }}" style="text-decoration: none; background: #1e293b; color: white; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 600; transition: 0.2s;" onmouseover="this.style.background='#000'" onmouseout="this.style.background='#1e293b'">
                                        Detail
                                    </a>
                                    <a href="{{ route('invoices.pdf', $invoice) }}" style="text-decoration: none; background: white; color: #1e293b; border: 1px solid #e2e8f0; padding: 8px 14px; border-radius: 10px; font-size: 12px; font-weight: 600; transition: 0.2s;" onmouseover="this.style.borderColor='#1e293b'" onmouseout="this.style.borderColor='#e2e8f0'">
                                        PDF
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding: 60px 24px; text-align: center;">
                                <div style="font-size: 40px; margin-bottom: 16px;">📂</div>
                                <p style="color: #94a3b8; font-weight: 500; font-size: 15px;">Belum ada invoice yang diterbitkan untuk akun Anda.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Styling --}}
            @if($invoices->hasPages())
            <div style="padding: 20px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
                {{ $invoices->links() }}
            </div>
            @endif
        </div>

        {{-- Help Banner --}}
        <div style="margin-top: 30px; background: #eff6ff; border: 1px solid #dbeafe; padding: 20px; border-radius: 20px; display: flex; align-items: center; gap: 15px;">
            <span style="font-size: 24px;">💡</span>
            <p style="font-size: 14px; color: #1e40af; margin: 0; line-height: 1.5;">
                <strong>Butuh bantuan pembayaran?</strong> Jika ada kesalahan pada nominal tagihan, silakan hubungi admin melalui menu <strong>Layanan Kamar</strong> untuk klarifikasi.
            </p>
        </div>

    </div>
</div>
@endsection