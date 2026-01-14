@extends('layouts.app')

@section('title', 'Daftar Invoice')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 20px; font-family: 'Inter', sans-serif;">
    
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin: 0;">Tagihan & Invoice</h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 14px;">Kelola semua invoice penyewa dan status pembayaran bulanan.</p>
        </div>
        {{-- Tombol aksi tambahan jika diperlukan --}}
        <div style="display: flex; gap: 10px;">
            <button style="background: white; border: 1px solid #e2e8f0; padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 600; color: #475569; cursor: pointer;">
                Export Excel
            </button>
        </div>
    </div>

    {{-- Invoice Table Card --}}
    <div style="background: white; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 18px 20px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Penyewa</th>
                    <th style="padding: 18px 20px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Periode</th>
                    <th style="padding: 18px 20px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Jatuh Tempo</th>
                    <th style="padding: 18px 20px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase;">Status</th>
                    <th style="padding: 18px 20px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: right;">Total Tagihan</th>
                    <th style="padding: 18px 20px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px; color: #334155;">
                @forelse ($invoices as $invoice)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 20px;">
                        <div style="font-weight: 700; color: #1e293b;">{{ $invoice->user->name ?? '—' }}</div>
                        <div style="font-size: 12px; color: #94a3b8; margin-top: 2px;">INV-#{{ $invoice->id }}</div>
                    </td>
                    <td style="padding: 20px;">
                        <span style="color: #475569; font-weight: 500;">{{ $invoice->period_month }}</span>
                    </td>
                    <td style="padding: 20px;">
                        <div style="color: #475569;">{{ optional($invoice->due_date)->format('d M Y') ?? '-' }}</div>
                    </td>
                    <td style="padding: 20px;">
                        @php
                            $statusStyles = match($invoice->status) {
                                'draft' => ['bg' => '#f1f5f9', 'text' => '#475569'],
                                'issued' => ['bg' => '#e0f2fe', 'text' => '#0369a1'],
                                'paid' => ['bg' => '#dcfce7', 'text' => '#15803d'],
                                'overdue' => ['bg' => '#fee2e2', 'text' => '#b91c1c'],
                                default => ['bg' => '#f1f5f9', 'text' => '#475569'],
                            };
                        @endphp
                        <span style="background: {{ $statusStyles['bg'] }}; color: {{ $statusStyles['text'] }}; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $invoice->status }}
                        </span>
                    </td>
                    <td style="padding: 20px; text-align: right;">
                        <span style="font-weight: 700; color: #1e293b; font-size: 15px;">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</span>
                    </td>
                    <td style="padding: 20px; text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <a href="{{ route('invoices.show', $invoice) }}" 
                               style="text-decoration: none; background: #ffffff; border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 6px; color: #3b82f6; font-size: 12px; font-weight: 600; transition: 0.2s;">
                                Detail
                            </a>
                            <a href="{{ route('invoices.pdf', $invoice) }}" 
                               style="text-decoration: none; background: #3b82f6; border: 1px solid #3b82f6; padding: 6px 12px; border-radius: 6px; color: white; font-size: 12px; font-weight: 600; transition: 0.2s;">
                                PDF
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 80px 20px; text-align: center;">
                        <div style="font-size: 40px; margin-bottom: 15px;">📄</div>
                        <div style="color: #1e293b; font-weight: 700; font-size: 16px;">Belum ada invoice</div>
                        <div style="color: #94a3b8; font-size: 14px; margin-top: 5px;">Invoice otomatis akan muncul saat periode tagihan dimulai.</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination Area --}}
        <div style="padding: 20px; border-top: 1px solid #f1f5f9; background: #fafafa;">
            {{ $invoices->links() }}
        </div>
    </div>
</div>

<style>
    /* Merapikan Pagination agar sesuai tema modern */
    .pagination { display: flex; list-style: none; gap: 5px; justify-content: center; margin: 0; padding: 0; }
    .page-item .page-link { padding: 8px 14px; border-radius: 8px; border: 1px solid #e2e8f0; color: #64748b; text-decoration: none; font-size: 13px; background: white; }
    .page-item.active .page-link { background: #3b82f6; color: white; border-color: #3b82f6; }
</style>
@endsection