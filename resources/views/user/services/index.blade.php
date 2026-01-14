@extends('layouts.app')

@section('title', 'Layanan Saya')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 1100px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Header Section --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 32px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
                    Layanan & Fasilitas
                </h1>
                <p style="color: #64748b; margin-top: 5px; font-size: 15px;">Ajukan permintaan laundry, perbaikan, atau layanan tambahan lainnya.</p>
            </div>
            <a href="{{ route('user.services.create') }}" style="text-decoration: none; background: #3b82f6; color: white; padding: 12px 24px; border-radius: 14px; font-weight: 700; font-size: 14px; display: flex; align-items: center; gap: 10px; transition: 0.2s; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25);" onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#3b82f6'; this.style.transform='translateY(0)'">
                <span>+</span> Ajukan Layanan Baru
            </a>
        </div>

        {{-- Filter Box --}}
        <div style="background: white; border-radius: 20px; padding: 20px; border: 1px solid #e2e8f0; margin-bottom: 30px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <form method="GET" action="{{ route('user.services.index') }}" style="display: flex; flex-wrap: wrap; gap: 12px; align-items: center;">
                <div style="flex: 1; min-width: 180px;">
                    <select name="service_type" style="width: 100%; padding: 10px 15px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; color: #475569; background: #fcfdfe;">
                        <option value="">Semua Jenis Layanan</option>
                        @foreach(['laundry' => 'Laundry', 'blanket' => 'Ganti Selimut', 'repair' => 'Perbaikan', 'other' => 'Lainnya'] as $key => $label)
                            <option value="{{ $key }}" {{ request('service_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1; min-width: 180px;">
                    <select name="status" style="width: 100%; padding: 10px 15px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px; color: #475569; background: #fcfdfe;">
                        <option value="">Semua Status</option>
                        @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'in_progress' => 'Proses', 'completed' => 'Selesai', 'rejected' => 'Ditolak'] as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 2; min-width: 250px; position: relative;">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari deskripsi layanan..." style="width: 100%; padding: 10px 15px; padding-left: 40px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 14px;">
                    <span style="position: absolute; left: 15px; top: 12px; color: #94a3b8;">🔍</span>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" style="background: #1e293b; color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer;">Filter</button>
                    <a href="{{ route('user.services.index') }}" style="text-decoration: none; background: #f1f5f9; color: #475569; padding: 10px 20px; border-radius: 10px; font-weight: 700; font-size: 14px;">Reset</a>
                </div>
            </form>
        </div>

        {{-- Content Card --}}
        <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Jenis Layanan</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Deskripsi</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Estimasi Biaya</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Status</th>
                            <th style="padding: 18px 24px; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px; color: #475569;">
                        @forelse($services as $service)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: 0.2s;" onmouseover="this.style.background='#fcfdfe'" onmouseout="this.style.background='transparent'">
                            <td style="padding: 20px 24px;">
                                <div style="display: flex; flex-direction: column;">
                                    <span style="font-weight: 800; color: #1e293b; font-size: 14px;">
                                        {{ $service->service_type_name ?? strtoupper($service->service_type) }}
                                    </span>
                                    <span style="font-size: 12px; color: #94a3b8; margin-top: 2px;">
                                        {{ $service->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </td>
                            <td style="padding: 20px 24px; max-width: 300px;">
                                <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; color: #64748b;">
                                    {{ $service->description }}
                                </div>
                            </td>
                            <td style="padding: 20px 24px; font-weight: 700; color: #1e293b;">
                                {{ $service->formatted_price ?? 'Menunggu Info' }}
                            </td>
                            <td style="padding: 20px 24px;">
                                {{-- Jika status_badge Anda adalah HTML dari Controller, ia akan merender di sini --}}
                                {!! $service->status_badge !!}
                            </td>
                            <td style="padding: 20px 24px; text-align: center;">
                                <a href="{{ route('user.services.show', $service) }}" style="text-decoration: none; display: inline-block; padding: 8px 16px; background: white; border: 1px solid #e2e8f0; color: #1e293b; border-radius: 10px; font-size: 12px; font-weight: 700; transition: 0.2s;" onmouseover="this.style.borderColor='#1e293b'; this.style.background='#f8fafc'" onmouseout="this.style.borderColor='#e2e8f0'; this.style.background='white'">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="padding: 80px 24px; text-align: center;">
                                <div style="font-size: 48px; margin-bottom: 20px;">🛠</div>
                                <h3 style="font-size: 18px; color: #1e293b; margin-bottom: 8px;">Belum Ada Permintaan</h3>
                                <p style="color: #94a3b8; font-weight: 500;">Anda dapat mengajukan layanan tambahan untuk kenyamanan kamar Anda.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($services->hasPages())
            <div style="padding: 20px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
                {{ $services->links() }}
            </div>
            @endif
        </div>

    </div>
</div>
@endsection