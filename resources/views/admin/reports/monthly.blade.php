@extends('layouts.app')

@section('title', 'Laporan Bulanan - ' . \Carbon\Carbon::parse($month)->format('F Y'))

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px; font-family: 'Inter', sans-serif; background-color: #f8fafc; min-height: 100vh;">
    
    {{-- Breadcrumb & Back --}}
    <div style="margin-bottom: 25px;">
        <a href="{{ route('admin.reports.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Rekap Laporan
        </a>
    </div>

    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; gap: 20px; flex-wrap: wrap;">
        <div>
            <h1 style="font-size: 32px; font-weight: 900; color: #1e293b; margin: 0; letter-spacing: -1px;">Laporan Bulanan</h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 15px;">Data transaksi periode <span style="color: #1e293b; font-weight: 700;">{{ \Carbon\Carbon::parse($month)->format('F Y') }}</span></p>
        </div>
        
        <div style="display: flex; gap: 15px; align-items: center;">
            {{-- Filter Form --}}
            <form action="{{ route('admin.reports.monthly') }}" method="GET" style="display: flex; gap: 10px;">
    {{-- Input ini memungkinkan user ganti tahun dan bulan sekaligus --}}
    <input type="month" 
           name="month" 
           value="{{ $month }}" 
           style="padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
           
    <button type="submit" style="background: #3b82f6; color: white; padding: 10px 20px; border-radius: 8px; border: none; font-weight: 600; cursor: pointer;">
        Tampilkan Laporan
    </button>
</form>

            {{-- Export Action --}}
            <form action="{{ route('admin.reports.export') }}" method="GET">
                <input type="hidden" name="month" value="{{ $month }}">
                <button type="submit" style="background: white; color: #1e293b; border: 1px solid #e2e8f0; padding: 13px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.2s; box-shadow: 0 1px 2px rgba(0,0,0,0.05);" onmouseover="this.style.background='#f8fafc'">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10 a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    PDF
                </button>
            </form>
        </div>
    </div>

    {{-- Highlight Stat Card --}}
    <div style="background: #1e293b; position: relative; overflow: hidden; padding: 35px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); margin-bottom: 40px; color: white;">
        {{-- Decorative element --}}
        <div style="position: absolute; right: -50px; top: -50px; width: 200px; height: 200px; background: rgba(255,255,255,0.03); border-radius: 50%;"></div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; position: relative; z-index: 1;">
            <div>
                <span style="font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; color: #94a3b8;">Total Pemasukan Bersih</span>
                <h2 style="font-size: 42px; font-weight: 900; margin: 10px 0 0 0; color: #10b981; letter-spacing: -1px;">
                    Rp {{ number_format($totalIncome, 0, ',', '.') }}
                </h2>
            </div>
            <div style="text-align: right;">
                <div style="background: rgba(16, 185, 129, 0.1); padding: 12px 24px; border-radius: 14px; border: 1px solid rgba(16, 185, 129, 0.2);">
                    <span style="display: block; font-size: 10px; text-transform: uppercase; color: #10b981; font-weight: 800; letter-spacing: 1px;">Status</span>
                    <span style="font-size: 15px; font-weight: 800; color: #fff;">FINALIZED ✅</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Transaction List --}}
    <div style="background: white; border: 1px solid #e2e8f0; border-radius: 24px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); overflow: hidden;">
        <div style="padding: 25px 30px; border-bottom: 1px solid #f1f5f9; background: #ffffff; display: flex; align-items: center; justify-content: space-between;">
            <h3 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;">Daftar Transaksi Masuk</h3>
            <span style="background: #f1f5f9; color: #475569; font-size: 12px; font-weight: 700; padding: 5px 12px; border-radius: 20px;">{{ count($payments) }} Transaksi</span>
        </div>
        
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
                        <th style="padding: 18px 30px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">No. Invoice</th>
                        <th style="padding: 18px 30px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Nama Penyewa</th>
                        <th style="padding: 18px 30px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Unit</th>
                        <th style="padding: 18px 30px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Nominal</th>
                        <th style="padding: 18px 30px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Tgl Bayar</th>
                        <th style="padding: 18px 30px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody style="font-size: 14px; color: #334155;">
                    @forelse($payments as $payment)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;" onmouseover="this.style.background='#fbfcfe'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 20px 30px; font-family: 'JetBrains Mono', 'Monaco', monospace; font-size: 13px; color: #64748b; font-weight: 600;">
                            #{{ $payment->invoice_number }}
                        </td>
                        <td style="padding: 20px 30px; font-weight: 700; color: #1e293b;">
                            {{ $payment->user->name }}
                        </td>
                        <td style="padding: 20px 30px;">
                            <span style="background: #eff6ff; color: #2563eb; padding: 6px 12px; border-radius: 8px; font-weight: 800; font-size: 12px; border: 1px solid #dbeafe;">
                                Room {{ $payment->room->room_number ?? '-' }}
                            </span>
                        </td>
                        <td style="padding: 20px 30px; font-weight: 800; color: #1e293b; font-size: 15px;">
                            {{ $payment->formatted_amount }}
                        </td>
                        <td style="padding: 20px 30px; color: #64748b; font-weight: 500;">
                            {{ $payment->verified_at ? $payment->verified_at->format('d M Y') : '-' }}
                        </td>
                        <td style="padding: 20px 30px; text-align: center;">
                            <a href="{{ route('admin.payments.show', $payment) }}" 
                               style="text-decoration: none; background: #1e293b; color: white; padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; transition: 0.3s; display: inline-block;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 100px 30px; text-align: center; color: #94a3b8;">
                            <div style="font-size: 50px; margin-bottom: 15px; filter: grayscale(1);">📂</div>
                            <div style="font-weight: 800; font-size: 18px; color: #1e293b;">Tidak ada transaksi</div>
                            <div style="font-size: 14px; margin-top: 5px; color: #64748b;">Belum ada data pembayaran untuk periode ini.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection