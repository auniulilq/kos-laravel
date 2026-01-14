@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Breadcrumb --}}
        @include('components.breadcrumb', ['breadcrumbs' => [
            ['title' => 'Dashboard', 'url' => route('user.dashboard')],
            ['title' => 'Pembayaran', 'url' => route('user.payments.index')]
        ]])
        
        {{-- Header Section --}}
        <div style="margin-bottom: 32px;">
            <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
                Riwayat Pembayaran
            </h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 15px;">Kelola dan pantau semua transaksi sewa kamar Anda.</p>
        </div>

        {{-- Filter Box --}}
        <div style="background: white; border-radius: 20px; padding: 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); margin-bottom: 32px;">
            <form method="GET" action="{{ route('user.payments.index') }}" style="display: flex; flex-wrap: wrap; gap: 15px; align-items: flex-end;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Status Transaksi</label>
                    <select name="status" style="width: 100%; padding: 10px 15px; border-radius: 12px; border: 1px solid #e2e8f0; background: #fcfdfe; color: #475569; font-size: 14px; cursor: pointer;">
                        <option value="">Semua Status</option>
                        @foreach(['pending' => 'Pending', 'paid' => 'Dibayar', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak'] as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1; min-width: 200px;">
                    <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 8px;">Bulan / Tahun</label>
                    <input type="month" name="month_year" value="{{ request('month_year') }}" style="width: 100%; padding: 10px 15px; border-radius: 12px; border: 1px solid #e2e8f0; background: #fcfdfe; color: #475569; font-size: 14px;">
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 11px 25px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                        Terapkan Filter
                    </button>
                    <a href="{{ route('user.payments.index') }}" style="text-decoration: none; background: #f1f5f9; color: #475569; padding: 11px 25px; border-radius: 12px; font-weight: 700; font-size: 14px; transition: 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                        Reset
                    </a>
                </div>
            </form>
        </div>
@if ($errors->any())
    <div style="background: #fee2e2; color: #b91c1c; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 13px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        {{-- Table Card --}}
        <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Invoice</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Unit</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Periode</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Total Tagihan</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px; color: #475569;">
                        @forelse($payments as $payment)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;" onmouseover="this.style.background='#fcfdfe'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 20px 24px;">
                                <span style="font-weight: 800; color: #1e293b;">#{{ $payment->invoice_number }}</span>
                            </td>
                            <td style="padding: 20px 24px;">
                                <span style="background: #eff6ff; color: #3b82f6; padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 12px;">
                                    {{ $payment->room->room_number }}
                                </span>
                            </td>
                            <td style="padding: 20px 24px;">
                                {{ \Carbon\Carbon::parse($payment->month_year)->format('F Y') }}
                            </td>
                            <td style="padding: 20px 24px; font-weight: 700; color: #1e293b;">
                                {{ $payment->formatted_amount }}
                            </td>
                            <td style="padding: 20px 24px;">
                                {!! $payment->status_badge !!}
                            </td>
                            <td style="padding: 20px 24px; text-align: center;">
                                <a href="{{ route('user.payments.show', $payment) }}" style="display: inline-block; text-decoration: none; background: #1e293b; color: white; padding: 8px 16px; border-radius: 10px; font-size: 12px; font-weight: 700; transition: 0.2s;" onmouseover="this.style.background='#000'; this.style.transform='translateY(-1px)'" onmouseout="this.style.background='#1e293b'; this.style.transform='translateY(0)'">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="padding: 80px 24px; text-align: center;">
                                <div style="font-size: 48px; margin-bottom: 20px;">💳</div>
                                <h3 style="font-size: 18px; color: #1e293b; margin-bottom: 8px;">Belum Ada Riwayat</h3>
                                <p style="color: #94a3b8; font-weight: 500;">Tagihan atau riwayat pembayaran Anda akan muncul di sini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payments->hasPages())
            <div style="padding: 20px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
                {{ $payments->links() }}
            </div>
            @endif
        </div>
        
    </div>
</div>
@endsection