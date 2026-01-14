@extends('layouts.app')

@section('title', 'Katalog Layanan')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 40px 20px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    {{-- Breadcrumb --}}
    @include('components.breadcrumb', ['breadcrumbs' => [
        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['title' => 'Katalog Layanan', 'url' => route('admin.service-options.index')]
    ]])
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 900; color: #1e293b; margin: 0; letter-spacing: -1px;">Katalog Layanan</h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 15px; font-weight: 500;">Kelola parameter biaya, unit satuan, dan ketersediaan opsi layanan tambahan.</p>
        </div>
        <a href="{{ route('admin.service-options.create') }}" 
           style="background: #1e293b; color: white; text-decoration: none; padding: 14px 28px; border-radius: 14px; font-weight: 700; font-size: 14px; transition: 0.3s; display: flex; align-items: center; gap: 10px; box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.2);"
           onmouseover="this.style.background='#0f172a'; this.style.transform='translateY(-2px)'" 
           onmouseout="this.style.background='#1e293b'; this.style.transform='translateY(0)'">
            <span style="font-size: 20px; line-height: 1;">+</span> Tambah Opsi Baru
        </a>
    </div>

    {{-- Filter & Search Card --}}
    <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid #e2e8f0; margin-bottom: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
        <form method="GET" action="{{ route('admin.service-options.index') }}" style="display:flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px; position: relative;">
                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama layanan (ex: Laundry Ekspress)..." 
                    style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc; outline: none; transition: 0.2s; font-size: 14px;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';">
            </div>

            <select name="service_type" style="padding: 12px 15px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc; color: #475569; font-weight: 700; font-size: 14px; outline: none; cursor: pointer;">
                <option value="">Semua Kategori</option>
                @foreach(['laundry' => '🧺 Laundry', 'blanket' => '🛌 Selimut', 'repair' => '🛠️ Perbaikan', 'other' => '📦 Lainnya'] as $key => $label)
                    <option value="{{ $key }}" {{ request('service_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>

            <button type="submit" style="background: #f1f5f9; color: #475569; border: none; padding: 12px 25px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.2s; font-size: 14px;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                Filter
            </button>
            
            @if(request('q') || request('service_type'))
                <a href="{{ route('admin.service-options.index') }}" style="text-decoration: none; color: #ef4444; font-size: 13px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;">Hapus Filter</a>
            @endif
        </form>
    </div>

    {{-- Table Card --}}
    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.04);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Info Layanan</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Pricing Model</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Tarif / Biaya</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Limit Unit</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Status</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px; color: #334155;">
                @forelse($options as $option)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.3s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 20px 25px;">
                        <div style="font-weight: 800; color: #1e293b; font-size: 15px;">{{ $option->name }}</div>
                        <div style="display: inline-block; margin-top: 6px; font-size: 10px; font-weight: 800; color: #3b82f6; background: #eff6ff; padding: 2px 8px; border-radius: 4px; text-transform: uppercase; border: 1px solid #dbeafe;">
                            {{ $option->service_type }}
                        </div>
                    </td>
                    <td style="padding: 20px 25px;">
                        @php
                            $styles = [
                                'fixed' => ['bg' => '#f0fdf4', 'text' => '#166534', 'label' => 'Harga Tetap'],
                                'per_unit' => ['bg' => '#eff6ff', 'text' => '#1e40af', 'label' => 'Per ' . ($option->unit_name ?? 'Unit')],
                                'quote' => ['bg' => '#fff7ed', 'text' => '#9a3412', 'label' => 'Nego / Quote']
                            ];
                            $currStyle = $styles[$option->pricing_type] ?? $styles['fixed'];
                        @endphp
                        <span style="background: {{ $currStyle['bg'] }}; color: {{ $currStyle['text'] }}; padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 800; text-transform: uppercase;">
                            {{ $currStyle['label'] }}
                        </span>
                    </td>
                    <td style="padding: 20px 25px;">
                        @if($option->pricing_type === 'quote')
                            <span style="color: #94a3b8; font-style: italic; font-weight: 500;">Menyesuaikan</span>
                        @else
                            <span style="font-weight: 800; color: #1e293b; font-size: 16px;">Rp{{ number_format($option->price, 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td style="padding: 20px 25px;">
                        @if($option->min_qty || $option->max_qty)
                            <div style="font-weight: 600; color: #475569;">
                                {{ $option->min_qty ?? 0 }} <span style="color: #cbd5e1; margin: 0 4px;">→</span> {{ $option->max_qty ?? '∞' }}
                            </div>
                        @else
                            <span style="color: #cbd5e1;">Tanpa Limit</span>
                        @endif
                    </td>
                    <td style="padding: 20px 25px;">
                        @if($option->is_active)
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #10b981; padding: 5px 12px; border-radius: 20px; font-weight: 800; font-size: 11px;">
                                <span style="width: 6px; height: 6px; background: #10b981; border-radius: 50%;"></span> Aktif
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; color: #94a3b8; padding: 5px 12px; border-radius: 20px; font-weight: 800; font-size: 11px;">
                                <span style="width: 6px; height: 6px; background: #cbd5e1; border-radius: 50%;"></span> Draft
                            </span>
                        @endif
                    </td>
                    <td style="padding: 20px; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center;">
                                <a href="{{ route('admin.service-options.show', $option) }}" style="padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; color: #1e293b; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6'">Detail</a>
                                <a href="{{ route('admin.service-options.edit', $option) }}" style="padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; color: #0891b2; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.2s;" onmouseover="this.style.borderColor='#0891b2'; this.style.background='#f0f9ff'">Edit</a>
                                <form action="{{ route('admin.service-options.destroy', $option) }}" method="POST" onsubmit="return confirm('Hapus layanan ini?')" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" style="padding: 8px 14px; background: white; border: 1px solid #fee2e2; border-radius: 10px; color: #ef4444; font-weight: 700; font-size: 12px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#fef2f2'">Hapus</button>
                                </form>
                            </div>
                        </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 100px 20px; text-align: center;">
                        <div style="background: #f1f5f9; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 35px;">📦</div>
                        <div style="font-weight: 800; font-size: 18px; color: #1e293b;">Belum Ada Layanan</div>
                        <p style="color: #94a3b8; font-size: 14px; max-width: 320px; margin: 8px auto 0;">Klik tombol "+ Tambah Opsi Baru" untuk mulai membangun katalog layanan Anda.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $options->links() }}
    </div>
</div>
@endsection