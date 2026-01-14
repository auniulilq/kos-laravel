@extends('layouts.app')

@section('title', 'Kelola Permintaan Layanan')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding: 30px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    {{-- Breadcrumb --}}
    @include('components.breadcrumb', ['breadcrumbs' => [
        ['title' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['title' => 'Pengajuan Layanan', 'url' => route('admin.services.index')]
    ]])
    {{-- Header Section --}}
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;">
        <div>
            <h1 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -1px;">Kelola Pengajuan Layanan</h1>
            <p style="color: #64748b; margin-top: 5px; font-size: 15px;">Pantau dan proses pengajuan layanan tambahan penyewa secara real-time.</p>
        </div>
        <div style="background: white; padding: 10px 20px; border-radius: 12px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 10px;">
            <span style="width: 10px; height: 10px; background: #10b981; border-radius: 50%; display: inline-block;"></span>
            <span style="font-size: 13px; font-weight: 700; color: #1e293b;">Sistem Aktif</span>
        </div>
    </div>

    {{-- Filter & Search Card --}}
    <div style="background: white; padding: 25px; border-radius: 20px; border: 1px solid #e2e8f0; margin-bottom: 30px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
        <form method="GET" action="{{ route('admin.services.index') }}" style="display:flex; gap: 15px; align-items: center; flex-wrap: wrap;">
            
            <div style="flex: 1; min-width: 280px; position: relative;">
                <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8;">🔍</span>
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari penyewa atau deskripsi..." 
                    style="width: 100%; padding: 12px 15px 12px 45px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc; outline: none; transition: 0.2s; font-size: 14px;"
                    onfocus="this.style.borderColor='#3b82f6'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)';">
            </div>

            <div style="display: flex; gap: 10px;">
                <select name="service_type" style="padding: 12px 15px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc; color: #475569; font-weight: 600; font-size: 14px; cursor: pointer; outline: none;">
                    <option value="">Semua Jenis</option>
                    @foreach(['laundry' => 'Laundry', 'blanket' => 'Selimut', 'repair' => 'Perbaikan', 'other' => 'Lainnya'] as $key => $label)
                        <option value="{{ $key }}" {{ request('service_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>

                <select name="status" style="padding: 12px 15px; border-radius: 12px; border: 1.5px solid #f1f5f9; background: #f8fafc; color: #475569; font-weight: 600; font-size: 14px; cursor: pointer; outline: none;">
                    <option value="">Semua Status</option>
                    @foreach(['pending' => 'Pending', 'approved' => 'Disetujui', 'in_progress' => 'Proses', 'completed' => 'Selesai', 'rejected' => 'Ditolak'] as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 15px;">
                <button type="submit" style="background: #1e293b; color: white; border: none; padding: 12px 30px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.2s; font-size: 14px;" onmouseover="this.style.background='#0f172a'" onmouseout="this.style.background='#1e293b'">
                    Terapkan
                </button>
                <a href="{{ route('admin.services.index') }}" style="text-decoration: none; color: #94a3b8; font-size: 14px; font-weight: 700; transition: 0.2s;" onmouseover="this.style.color='#ef4444'">Reset</a>
            </div>
        </form>
    </div>

    {{-- Table Card --}}
    <div style="background: white; border-radius: 20px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.04);">
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="background: #fafafa; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Waktu Permintaan</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Informasi Penyewa</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Layanan</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Estimasi Biaya</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Status</th>
                    <th style="padding: 20px 25px; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 800; letter-spacing: 1px; text-align: center;">Tindakan</th>
                </tr>
            </thead>
            <tbody style="font-size: 14px; color: #334155;">
                @forelse($services as $service)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.3s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 20px 25px;">
                        <div style="font-weight: 700; color: #1e293b;">{{ $service->created_at->translatedFormat('d M Y') }}</div>
                        <div style="font-size: 12px; color: #94a3b8; font-weight: 500;">{{ $service->created_at->format('H:i') }} WIB</div>
                    </td>
                    <td style="padding: 20px 25px;">
                        <div style="font-weight: 800; color: #1e293b; font-size: 15px;">{{ $service->user->name }}</div>
                        @if($service->room)
                            <div style="margin-top: 4px;">
                                <span style="font-size: 10px; background: #eff6ff; color: #2563eb; padding: 4px 10px; border-radius: 6px; font-weight: 800; text-transform: uppercase; border: 1px solid #dbeafe;">
                                    Unit {{ $service->room->room_number }}
                                </span>
                            </div>
                        @else
                            <span style="color: #cbd5e1; font-size: 12px;">N/A Room</span>
                        @endif
                    </td>
                    <td style="padding: 20px 25px;">
                        <div style="font-weight: 700; color: #475569; display: flex; align-items: center; gap: 8px;">
                            <span style="font-size: 18px;">
                                @if($service->service_type === 'laundry') 
                                @elseif($service->service_type === 'repair')  
                                @else  @endif
                            </span>
                            {{ $service->service_type_name }}
                        </div>
                    </td>
                    <td style="padding: 20px 25px;">
                        <span style="font-weight: 900; color: #1e293b; font-size: 16px;">{{ $service->formatted_price ?? 'Rp 0' }}</span>
                    </td>
                    <td style="padding: 20px 25px;">
                        <div style="transform: scale(0.95); transform-origin: left;">
                            {!! $service->status_badge !!}
                        </div>
                    </td>
                    <td style="padding: 20px 25px; text-align: center;">
                          <a href="{{ route('admin.services.show', $service) }}" style="padding: 8px 14px; background: white; border: 1px solid #e2e8f0; border-radius: 10px; color: #1e293b; text-decoration: none; font-weight: 700; font-size: 12px; transition: 0.2s;" onmouseover="this.style.borderColor='#3b82f6'; this.style.color='#3b82f6'">Detail</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 100px 20px; text-align: center;">
                        <div style="background: #f8fafc; width: 80px; height: 80px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                            <span style="font-size: 40px;">📂</span>
                        </div>
                        <div style="font-weight: 800; font-size: 18px; color: #1e293b;">Data Tidak Ditemukan</div>
                        <p style="color: #94a3b8; font-size: 14px; max-width: 300px; margin: 5px auto 0;">Gunakan kata kunci lain atau periksa filter status Anda.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="margin-top: 30px; display: flex; justify-content: center;">
        {{ $services->links() }}
    </div>
</div>

<style>
    /* Styling tambahan untuk menyesuaikan elemen pagination Laravel agar senada */
    .pagination { display: flex; gap: 5px; list-style: none; }
    .page-item .page-link { border-radius: 8px !important; border: 1.5px solid #e2e8f0 !important; color: #475569 !important; font-weight: 600 !important; }
    .page-item.active .page-link { background-color: #3b82f6 !important; border-color: #3b82f6 !important; color: white !important; }
    
    /* Overwrite badge style jika diperlukan */
    .badge { padding: 6px 12px !important; border-radius: 8px !important; font-size: 11px !important; font-weight: 800 !important; text-transform: uppercase !important; }
</style>
@endsection