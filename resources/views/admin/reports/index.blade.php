@extends('layouts.app')

@section('title', 'Laporan Pemasukan')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    {{-- Breadcrumb --}}
    @include('components.breadcrumb', ['breadcrumbs' => [
        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['title' => 'Laporan Pemasukan', 'url' => route('admin.reports.index')]
    ]])
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px; flex-wrap: wrap; gap: 20px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 900; color: #1e293b; margin: 0; letter-spacing: -1px;">Laporan Pemasukan</h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 15px;">
                Periode: <span style="font-weight: 700; color: #3b82f6;">{{ Carbon\Carbon::parse($startDate)->format('d M Y') }}</span> s/d <span style="font-weight: 700; color: #3b82f6;">{{ Carbon\Carbon::parse($endDate)->format('d M Y') }}</span>
            </p>
        </div>
        
        <div style="display: flex; gap: 12px;">
            {{-- Export PDF --}}
            <form action="{{ route('admin.reports.export') }}" method="GET">
                <input type="hidden" name="start_date" value="{{ $startDate }}">
                <input type="hidden" name="end_date" value="{{ $endDate }}">
                <button type="submit" style="background: white; color: #1e293b; border: 1.5px solid #e2e8f0; padding: 10px 20px; border-radius: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: 0.3s; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <span>📄</span> Export PDF
                </button>
            </form>
        </div>
    </div>

    {{-- Filter Card --}}
    <div style="background: #1e293b; padding: 25px; border-radius: 20px; margin-bottom: 35px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);">
        <form action="{{ route('admin.reports.index') }}" method="GET" style="display: flex; gap: 20px; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; display: block;">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #334155; background: #334155; color: white; outline: none; font-weight: 600;">
            </div>
            <div style="flex: 1; min-width: 200px;">
                <label style="color: #94a3b8; font-size: 12px; font-weight: 700; text-transform: uppercase; margin-bottom: 8px; display: block;">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $endDate }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1px solid #334155; background: #334155; color: white; outline: none; font-weight: 600;">
            </div>
            <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 12px 30px; border-radius: 10px; font-weight: 700; cursor: pointer; transition: 0.2s; height: 48px;">
                Terapkan Filter
            </button>
        </form>
    </div>

    {{-- Stats Grid --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; margin-bottom: 40px;">
        <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #e2e8f0; position: relative; overflow: hidden;">
            <span style="color: #64748b; font-size: 13px; font-weight: 700; text-transform: uppercase;">Total Pemasukan</span>
            <h2 style="font-size: 32px; font-weight: 900; color: #10b981; margin: 10px 0;">Rp {{ number_format($monthlyIncome, 0, ',', '.') }}</h2>
            <div style="display: inline-flex; align-items: center; gap: 5px; color: #10b981; font-size: 12px; font-weight: 700; background: #f0fdf4; padding: 4px 10px; border-radius: 8px;">
                ✓ Terverifikasi Sistem
            </div>
        </div>

        <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #e2e8f0;">
            <span style="color: #64748b; font-size: 13px; font-weight: 700; text-transform: uppercase;">Volume Transaksi</span>
            <h2 style="font-size: 32px; font-weight: 900; color: #3b82f6; margin: 10px 0;">{{ $monthlyPayments->count() }}</h2>
            <p style="color: #94a3b8; font-size: 13px; margin: 0;">Transaksi berhasil dalam periode ini</p>
        </div>
    </div>

    {{-- Table Section --}}
    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
        <div style="padding: 25px 30px; border-bottom: 1px solid #f1f5f9;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 800; color: #1e293b;">Rincian Transaksi</h3>
        </div>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc; text-align: left;">
                        <th style="padding: 20px 30px; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Invoice</th>
                        <th style="padding: 20px 30px; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Penyewa</th>
                        <th style="padding: 20px 30px; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Kamar</th>
                        <th style="padding: 20px 30px; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Nominal</th>
                        <th style="padding: 20px 30px; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px;">Tanggal</th>
                        <th style="padding: 20px 30px; color: #94a3b8; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monthlyPayments as $payment)
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'">
                        <td style="padding: 20px 30px; font-family: monospace; font-weight: 700; color: #64748b;">#{{ $payment->invoice_number }}</td>
                        <td style="padding: 20px 30px; font-weight: 700; color: #1e293b;">{{ $payment->user->name }}</td>
                        <td style="padding: 20px 30px;"><span style="background: #eff6ff; color: #3b82f6; padding: 5px 10px; border-radius: 8px; font-size: 12px; font-weight: 800;">🚪 {{ $payment->room->room_number ?? '-' }}</span></td>
                        <td style="padding: 20px 30px; font-weight: 800; color: #1e293b;">{{ $payment->formatted_amount }}</td>
                        <td style="padding: 20px 30px; color: #64748b; font-size: 13px;">{{ $payment->verified_at->format('d M Y') }}</td>
                        <td style="padding: 20px 30px; text-align: center;">
                            <a href="{{ route('admin.payments.show', $payment) }}" style="text-decoration: none; color: #3b82f6; font-weight: 800; font-size: 12px; text-transform: uppercase;">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="padding: 80px 30px; text-align: center;">
                            <div style="font-size: 40px; margin-bottom: 10px;">Empty</div>
                            <p style="color: #94a3b8;">Tidak ada data transaksi ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection