@extends('layouts.app')

@section('title', 'Edit Opsi Layanan')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding: 20px; font-family: 'Inter', sans-serif;">
    
    {{-- Header --}}
    <div style="margin-bottom: 30px;">
        <a href="{{ route('admin.service-options.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; transition: 0.2s;">
            ← Kembali ke Katalog
        </a>
        <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin: 10px 0 0 0;">Edit Opsi Layanan</h1>
        <p style="color: #64748b; margin-top: 5px;">Perbarui detail harga dan pengaturan layanan operasional.</p>
    </div>

    {{-- Form Card --}}
    <div style="background: white; padding: 35px; border-radius: 20px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);">
        <form method="POST" action="{{ route('admin.service-options.update', $option) }}" style="display: grid; gap: 25px;">
            @csrf
            @method('PUT')

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                {{-- Jenis Layanan --}}
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Kategori Layanan</label>
                    <select name="service_type" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 14px; outline: none; transition: 0.2s;" required onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';">
                        @foreach(['laundry' => 'Laundry', 'blanket' => 'Cuci Selimut', 'repair' => 'Perbaikan', 'other' => 'Lainnya'] as $key => $label)
                            <option value="{{ $key }}" {{ old('service_type', $option->service_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('service_type') <div style="color: #ef4444; font-size: 12px; margin-top: 5px; font-weight: 600;">{{ $message }}</div> @enderror
                </div>

                {{-- Nama Opsi --}}
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Nama Layanan</label>
                    <input type="text" name="name" value="{{ old('name', $option->name) }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 14px; outline: none; transition: 0.2s;" required onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';">
                    @error('name') <div style="color: #ef4444; font-size: 12px; margin-top: 5px; font-weight: 600;">{{ $message }}</div> @enderror
                </div>
            </div>

            <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 10px 0;">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                {{-- Metode Harga --}}
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Metode Biaya</label>
                    <select name="pricing_type" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 14px; outline: none;" required>
                        @foreach(['fixed' => 'Harga Tetap (Fixed)', 'per_unit' => 'Berdasarkan Unit', 'quote' => 'Melalui Penawaran (Quote)'] as $key => $label)
                            <option value="{{ $key }}" {{ old('pricing_type', $option->pricing_type) === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Harga --}}
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Tarif Biaya (IDR)</label>
                    <input type="number" name="price" value="{{ old('price', $option->price) }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 14px; outline: none;" placeholder="Contoh: 50000">
                    @error('price') <div style="color: #ef4444; font-size: 12px; margin-top: 5px; font-weight: 600;">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 15px;">
                {{-- Unit Name --}}
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Nama Satuan</label>
                    <input type="text" name="unit_name" value="{{ old('unit_name', $option->unit_name) }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 14px; outline: none;" placeholder="kg, pcs, item">
                </div>

                {{-- Min Qty --}}
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Min. Order</label>
                    <input type="number" name="min_qty" value="{{ old('min_qty', $option->min_qty) }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 14px; outline: none;">
                </div>

                {{-- Max Qty --}}
                <div>
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Max. Order</label>
                    <input type="number" name="max_qty" value="{{ old('max_qty', $option->max_qty) }}" style="width: 100%; padding: 12px; border-radius: 10px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 14px; outline: none;">
                </div>
            </div>

            {{-- Status Checkbox --}}
            <div style="background: #f8fafc; padding: 15px; border-radius: 12px; border: 1px solid #f1f5f9;">
                <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $option->is_active) ? 'checked' : '' }} 
                           style="width: 18px; height: 18px; cursor: pointer; accent-color: #3b82f6;">
                    <div>
                        <span style="display: block; font-size: 14px; font-weight: 700; color: #1e293b;">Aktifkan Layanan</span>
                        <span style="display: block; font-size: 12px; color: #64748b;">Layanan akan muncul di aplikasi penyewa jika dicentang.</span>
                    </div>
                </label>
            </div>

            {{-- Action Buttons --}}
            <div style="display: flex; gap: 12px; margin-top: 10px;">
                <button type="submit" style="flex: 2; background: #1e293b; color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#334155'">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.service-options.index') }}" style="flex: 1; text-align: center; text-decoration: none; background: white; color: #64748b; border: 1px solid #e2e8f0; padding: 14px; border-radius: 12px; font-weight: 700; font-size: 15px; transition: 0.2s;" onmouseover="this.style.background='#f8fafc'">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection