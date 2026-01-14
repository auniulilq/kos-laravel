@extends('layouts.app')

@section('title', 'Detail ' . $serviceOption->name)

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 40px 20px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    {{-- Breadcrumb & Back --}}
    <div style="margin-bottom: 25px;">
        <a href="{{ route('admin.service-options.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px;">
            ← Kembali ke Katalog
        </a>
    </div>

    {{-- Main Detail Card --}}
    <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.04);">
        
        {{-- Header Section --}}
        <div style="padding: 30px; border-bottom: 1px solid #f1f5f9; background: #fafafa; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; color: #3b82f6; letter-spacing: 1px;">Informasi Layanan</span>
                <h1 style="font-size: 28px; font-weight: 900; color: #1e293b; margin: 5px 0 0; letter-spacing: -0.5px;">{{ $serviceOption->name }}</h1>
            </div>
            <a href="{{ route('admin.service-options.edit', $serviceOption) }}" style="padding: 10px 20px; background: #3b82f6; color: white; text-decoration: none; border-radius: 12px; font-weight: 700; font-size: 13px; transition: 0.2s;" onmouseover="this.style.background='#2563eb'">
                Edit Layanan
            </a>
        </div>

        {{-- Content Section --}}
        <div style="padding: 30px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                
                {{-- Left Column --}}
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Kategori</label>
                        <div style="font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                            <span style="background: #f1f5f9; padding: 4px 10px; border-radius: 6px; font-size: 13px;">
                                {{ $serviceOption->service_type_name ?? ucfirst($serviceOption->service_type) }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Pricing Model</label>
                        <div style="font-weight: 700; color: #1e293b; font-size: 15px;">
                            {{ ucfirst(str_replace('_', ' ', $serviceOption->pricing_type)) }}
                        </div>
                    </div>
                </div>

                {{-- Right Column --}}
                <div style="display: flex; flex-direction: column; gap: 20px;">
                    <div>
                        <label style="display: block; font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Biaya / Tarif</label>
                        <div style="font-size: 24px; font-weight: 900; color: #059669;">
                            @if($serviceOption->pricing_type === 'quote')
                                <span style="color: #94a3b8; font-size: 16px;">Berdasarkan Negosiasi</span>
                            @else
                                Rp{{ number_format($serviceOption->price, 0, ',', '.') }}
                                <span style="font-size: 14px; color: #64748b; font-weight: 500;">/ {{ $serviceOption->unit_name ?? 'Unit' }}</span>
                            @endif
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 6px;">Status Ketersediaan</label>
                        @if($serviceOption->is_active)
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #ecfdf5; color: #10b981; padding: 6px 14px; border-radius: 20px; font-weight: 800; font-size: 12px;">
                                ● Aktif & Muncul di Katalog
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; gap: 6px; background: #fef2f2; color: #ef4444; padding: 6px 14px; border-radius: 20px; font-weight: 800; font-size: 12px;">
                                ○ Non-aktif (Draft)
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Description Section --}}
            @if($serviceOption->description)
            <div style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #f1f5f9;">
                <label style="display: block; font-size: 12px; color: #94a3b8; font-weight: 700; text-transform: uppercase; margin-bottom: 10px;">Deskripsi Layanan</label>
                <p style="color: #475569; line-height: 1.6; font-size: 15px; margin: 0;">
                    {{ $serviceOption->description }}
                </p>
            </div>
            @endif
        </div>

        {{-- Stats / Info Footer --}}
        <div style="background: #f8fafc; padding: 20px 30px; display: flex; gap: 20px;">
            <div style="font-size: 12px; color: #94a3b8;">
                Dibuat pada: <strong>{{ $serviceOption->created_at->format('d M Y') }}</strong>
            </div>
            <div style="font-size: 12px; color: #94a3b8;">
                Terakhir diupdate: <strong>{{ $serviceOption->updated_at->diffForHumans() }}</strong>
            </div>
        </div>
    </div>
</div>
@endsection