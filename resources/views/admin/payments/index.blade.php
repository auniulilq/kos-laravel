@extends('layouts.app')

@section('title', 'Kelola Pembayaran')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 25px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    {{-- Breadcrumb --}}
    @include('components.breadcrumb', ['breadcrumbs' => [
        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['title' => 'Pembayaran', 'url' => route('admin.payments.index')]
    ]])
    
    {{-- Page Header --}}
    <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <h1 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -1px;">Kelola Pembayaran</h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 15px;">Pantau, verifikasi, dan kelola sirkulasi keuangan penyewa.</p>
        </div>
        {{-- Status Summary (Optional Addition) --}}
        <div style="display: flex; gap: 15px;">
            <div style="background: white; padding: 10px 20px; border-radius: 12px; border: 1px solid #e2e8f0; text-align: center;">
                <span style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Perlu Verifikasi</span>
                <span style="font-size: 18px; font-weight: 800; color: #f59e0b;">{{ $payments->where('status', 'paid')->count() }}</span>
            </div>
        </div>
    </div>

    {{-- Grid: Quick Actions & Filter --}}
    <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 25px; margin-bottom: 30px;">
        
        {{-- Quick Actions: Bulk Generate --}}
        <div style="background: white; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); position: relative; overflow: hidden;">
            <div style="position: absolute; right: -10px; top: -10px; font-size: 60px; opacity: 0.05;">🧾</div>
            <h3 style="font-size: 16px; font-weight: 800; color: #1e293b; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <span style="background: #dcfce7; padding: 8px; border-radius: 10px;">🔄</span> Generate Tagihan Masal
            </h3>
            <form action="{{ route('admin.payments.generateBulk') }}" method="POST">
                @csrf
                <div style="display: flex; gap: 10px;">
                    <input type="month" name="month_year" value="{{ date('Y-m') }}" required 
                           style="flex: 1; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; font-size: 14px; font-weight: 600;">
                    <button type="submit" style="background: #10b981; color: white; border: none; padding: 0 20px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);" onmouseover="this.style.background='#059669'" onmouseout="this.style.background='#10b981'">
                        Generate
                    </button>
                </div>
                <p style="font-size: 12px; color: #94a3b8; margin-top: 12px; line-height: 1.4;">* Sistem akan membuat tagihan sewa otomatis untuk semua unit yang sedang terisi.</p>
            </form>
        </div>

        {{-- Filter Section --}}
        <div style="background: white; border-radius: 20px; padding: 25px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <h3 style="font-size: 16px; font-weight: 800; color: #1e293b; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                <span style="background: #eff6ff; padding: 8px; border-radius: 10px;">🔍</span> Filter Transaksi
            </h3>
            <form method="GET" style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 15px; align-items: end;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Status Pembayaran</label>
                    <select name="status" style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 13px; font-weight: 600; cursor: pointer;">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Menunggu Pembayaran</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>💰 Sudah Bayar (Verifikasi)</option>
                        <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>✅ Terverifikasi</option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Periode Bulan</label>
                    <input type="month" name="month_year" value="{{ request('month_year') }}" style="width: 100%; padding: 11px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 13px; font-weight: 600;">
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" style="background: #1e293b; color: white; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 13px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#000'">Cari</button>
                    <a href="{{ route('admin.payments.index') }}" style="background: #f1f5f9; color: #64748b; border: none; text-decoration: none; padding: 12px 15px; border-radius: 12px; font-size: 13px; font-weight: 700; transition: 0.2s;" onmouseover="this.style.background='#e2e8f0'">Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Payments Table --}}
    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 1px solid #f1f5f9;">
                    <th style="padding: 20px 25px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Info Tagihan</th>
                    <th style="padding: 20px 25px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Kamar</th>
                    <th style="padding: 20px 25px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Periode</th>
                    <th style="padding: 20px 25px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Total Bayar</th>
                    <th style="padding: 20px 25px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Status</th>
                    <th style="padding: 20px 25px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Bukti TF</th>
                    <th style="padding: 20px 25px; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px;">
                @forelse($payments as $payment)
                <tr style="border-bottom: 1px solid #f8fafc; transition: 0.2s; {{ $payment->status === 'paid' ? 'background: #fffbeb;' : '' }}" onmouseover="this.style.background='{{ $payment->status === 'paid' ? '#fef3c7' : '#f8fafc' }}'" onmouseout="this.style.background='{{ $payment->status === 'paid' ? '#fffbeb' : 'transparent' }}'">
                    <td style="padding: 20px 25px;">
                        <div style="font-weight: 800; color: #1e293b; font-size: 15px;">#{{ $payment->invoice_number }}</div>
                        <div style="font-size: 13px; color: #64748b; margin-top: 2px;">{{ $payment->user->name }}</div>
                    </td>
                    <td style="padding: 20px 25px;">
                        <span style="background: #3b82f6; color: white; padding: 5px 12px; border-radius: 8px; font-weight: 800; font-size: 12px;">
                            {{ $payment->room->room_number }}
                        </span>
                    </td>
                    <td style="padding: 20px 25px; color: #475569; font-weight: 600;">
                        {{ \Carbon\Carbon::parse($payment->month_year)->format('F Y') }}
                    </td>
                    <td style="padding: 20px 25px; font-weight: 800; color: #10b981; font-size: 15px;">
                        {{ $payment->formatted_amount }}
                    </td>
                    <td style="padding: 20px 25px;">
                        {!! $payment->status_badge !!}
                    </td>
                    <td style="padding: 20px 25px;">
                        @if($payment->proof_image)
                            <a href="{{ asset('storage/' . $payment->proof_image) }}" target="_blank" 
                               style="text-decoration: none; color: #3b82f6; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                                <span style="background: #eff6ff; padding: 5px; border-radius: 6px;">📸</span> Lihat Bukti
                            </a>
                        @else
                            <span style="color: #cbd5e1; font-size: 12px; font-style: italic;">Belum diunggah</span>
                        @endif
                    </td>
                    <td style="padding: 20px 25px; text-align: center;">
                    <a href="{{ route('admin.payments.show', $payment) }}" style="padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; color: #1e293b; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6'">Detail</a>

                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="padding: 80px 25px; text-align: center;">
                        <div style="font-size: 50px; margin-bottom: 15px;">💸</div>
                        <div style="font-weight: 800; color: #1e293b; font-size: 18px;">Belum Ada Transaksi</div>
                        <p style="color: #94a3b8; font-size: 14px; margin-top: 5px;">Data pembayaran akan muncul di sini setelah tagihan dibuat.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        @if($payments->hasPages())
        <div style="padding: 25px; border-top: 1px solid #f1f5f9; background: #fafafa;">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>

<style>
    /* Styling Badge Laravel Model agar lebih modern */
    .badge { 
        padding: 6px 12px !important; 
        border-radius: 8px !important; 
        font-size: 11px !important; 
        font-weight: 800 !important; 
        text-transform: uppercase !important;
        letter-spacing: 0.5px;
    }
    .badge-success { background: #dcfce7 !important; color: #15803d !important; border: 1px solid #bbf7d0; }
    .badge-warning { background: #fff7ed !important; color: #9a3412 !important; border: 1px solid #ffedd5; }
    .badge-danger { background: #fee2e2 !important; color: #b91c1c !important; border: 1px solid #fecaca; }
    
    /* Hover effect for Table Pagination */
    .pagination svg { width: 20px; }
</style>
@endsection