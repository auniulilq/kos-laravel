@extends('layouts.app')

@section('title', 'Tambah Opsi Layanan')

@section('content')
<div style="max-width: 850px; margin: 0 auto; padding: 40px 20px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    {{-- Header --}}
    <div style="margin-bottom: 35px;">
        <a href="{{ route('admin.service-options.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Katalog
        </a>
        <h1 style="font-size: 32px; font-weight: 900; color: #1e293b; margin: 15px 0 5px 0; letter-spacing: -1px;">Konfigurasi Layanan Baru</h1>
        <p style="color: #64748b; font-size: 16px;">Tentukan kategori, skema harga, dan batasan operasional layanan.</p>
    </div>

    {{-- Form Card --}}
    <div style="background: white; padding: 40px; border-radius: 28px; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.03);">
        <form method="POST" action="{{ route('admin.service-options.store') }}" style="display: flex; flex-direction: column; gap: 30px;">
            @csrf

            {{-- Section 1: Identitas --}}
            <div>
                <h3 style="font-size: 14px; font-weight: 800; color: #3b82f6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    Opsi Layanan
                </h3>
                <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 25px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Nama Opsi Layanan</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Cuci Kering Kilat 6 Jam" style="width: 100%; padding: 14px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 15px; outline: none; transition: 0.3s;" required onfocus="this.style.borderColor='#3b82f6'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(59, 130, 246, 0.1)';">
                        @error('name') <div style="color: #ef4444; font-size: 12px; margin-top: 6px; font-weight: 600;">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Kategori Utama</label>
                        <select name="service_type" style="width: 100%; padding: 14px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 15px; outline: none; cursor: pointer; transition: 0.3s;" required onfocus="this.style.borderColor='#3b82f6';">
                            <option value="" disabled selected>Pilih Kategori...</option>
                            @foreach(['laundry' => ' Laundry', 'blanket' => ' Cuci Selimut', 'repair' => ' Perbaikan', 'other' => ' Lainnya'] as $key => $label)
                                <option value="{{ $key }}" {{ old('service_type') === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <hr style="border: 0; border-top: 1px dashed #e2e8f0;">

            {{-- Section 2: Finansial & Satuan --}}
            <div>
                <h3 style="font-size: 14px; font-weight: 800; color: #10b981; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                    Skema Harga
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Metode Penagihan</label>
                        <select name="pricing_type" style="width: 100%; padding: 14px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 15px; outline: none;" required>
                            <option value="fixed">Harga Tetap (Fixed)</option>
                            <option value="per_unit">Berdasarkan Satuan (Qty)</option>
                            <option value="quote">Sesuai Penawaran (Nego)</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Tarif Biaya (IDR)</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-weight: 700; font-size: 14px;">Rp</span>
                            <input type="number" name="price" value="{{ old('price') }}" style="width: 100%; padding: 14px 14px 14px 40px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 15px; font-weight: 700; outline: none;" placeholder="0">
                        </div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px; margin-top: 20px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 6px;">Nama Satuan</label>
                        <input type="text" name="unit_name" value="{{ old('unit_name') }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 14px;" placeholder="Kg / Pcs / Meter">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 6px;">Min. Order</label>
                        <input type="number" name="min_qty" value="{{ old('min_qty', 0) }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 700; color: #64748b; margin-bottom: 6px;">Max. Order</label>
                        <input type="number" name="max_qty" value="{{ old('max_qty') }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; font-size: 14px;" placeholder="∞">
                    </div>
                </div>
            </div>

            {{-- Section 3: Status --}}
            <div style="background: #f0f9ff; padding: 25px; border-radius: 20px; border: 1.5px solid #bae6fd;">
                <label style="display: flex; align-items: flex-start; gap: 18px; cursor: pointer;">
                    <div style="margin-top: 3px;">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} 
                               style="width: 22px; height: 22px; cursor: pointer; accent-color: #0ea5e9; border-radius: 6px;">
                    </div>
                    <div>
                        <span style="display: block; font-size: 15px; font-weight: 800; color: #0369a1; margin-bottom: 4px;">Publikasikan Layanan</span>
                        <span style="display: block; font-size: 13px; color: #0ea5e9; line-height: 1.5;">Aktifkan pilihan ini agar layanan langsung muncul di aplikasi penyewa dan dapat dipesan segera.</span>
                    </div>
                </label>
            </div>

            {{-- Action Buttons --}}
            <div style="display: flex; gap: 15px; margin-top: 15px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                <button type="submit" style="flex: 1; background: #1e293b; color: white; border: none; padding: 18px; border-radius: 16px; font-weight: 800; font-size: 16px; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.2);" onmouseover="this.style.background='#0f172a'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#1e293b'; this.style.transform='translateY(0)'">
                   Simpan
                </button>
                <a href="{{ route('admin.service-options.index') }}" style="flex: 1; text-align: center; text-decoration: none; background: white; color: #64748b; border: 1.5px solid #e2e8f0; padding: 18px; border-radius: 16px; font-weight: 700; font-size: 16px; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'; this.style.color='#1e293b'">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection