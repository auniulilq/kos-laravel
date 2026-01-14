@extends('layouts.app')

@section('title', 'Ajukan Layanan Baru')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Header --}}
        <div style="margin-bottom: 30px;">
            <a href="{{ route('user.services.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; margin-bottom: 15px;">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
            <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin: 0;">Ajukan Layanan Baru</h1>
            <p style="color: #64748b; margin-top: 5px;">Pilih jenis layanan yang Anda butuhkan untuk kenyamanan kamar Anda.</p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr; gap: 25px;">
            
            {{-- Form Card --}}
            <div style="background: white; border-radius: 24px; padding: 35px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
                <form action="{{ route('user.services.store') }}" method="POST">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
                        {{-- Service Type --}}
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Jenis Layanan</label>
                            <select id="service_type" name="service_type" required style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid #e2e8f0; background: #fcfdfe; color: #1e293b; font-size: 15px; font-weight: 500;">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="laundry" {{ old('service_type') === 'laundry' ? 'selected' : '' }}>Laundry</option>
                                <option value="blanket" {{ old('service_type') === 'blanket' ? 'selected' : '' }}>Cuci Selimut</option>
                                <option value="repair" {{ old('service_type') === 'repair' ? 'selected' : '' }}>Perbaikan</option>
                                <option value="other" {{ old('service_type') === 'other' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        {{-- Service Option --}}
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Opsi & Spesifikasi</label>
                            <select id="service_option_id" name="service_option_id" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1px solid #e2e8f0; background: #fcfdfe; color: #1e293b; font-size: 15px; font-weight: 500;">
                                <option value="">-- Pilih Opsi --</option>
                            </select>
                        </div>
                    </div>

                    {{-- Quantity Section (Hidden by default) --}}
                    <div id="qty_wrapper" style="display:none; margin-bottom: 25px; padding: 20px; background: #f8fafc; border-radius: 16px; border: 1px solid #f1f5f9;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 10px;">Jumlah (<span id="unit_name">unit</span>)</label>
                        <input type="number" id="quantity" name="quantity" min="1" value="{{ old('quantity', 1) }}" style="width: 120px; padding: 10px 15px; border-radius: 10px; border: 1px solid #e2e8f0; font-weight: 700; font-size: 16px; color: #1e293b;">
                    </div>

                    {{-- Description --}}
                    <div style="margin-bottom: 25px;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 10px; text-transform: uppercase;">Deskripsi / Kebutuhan</label>
                        <textarea name="description" rows="4" placeholder="Contoh: AC bocor di bagian dalam, atau laundry express 1 hari..." required style="width: 100%; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px; line-height: 1.5;">{{ old('description') }}</textarea>
                    </div>

                    {{-- Dynamic Pricing Box --}}
                    <div id="price_box" style="display: none; background: #1e293b; border-radius: 18px; padding: 25px; color: white; margin-bottom: 30px; position: relative; overflow: hidden;">
                        <div style="position: relative; z-index: 2;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; opacity: 0.8; font-size: 13px;">
                                <span>Estimasi Biaya Satuan:</span>
                                <span id="unit_price" style="font-weight: 600;">-</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <span style="font-weight: 700; font-size: 16px;">Total Estimasi:</span>
                                <span id="total_price" style="font-size: 24px; font-weight: 900; color: #3b82f6;">-</span>
                            </div>
                            <div id="quote_note" style="display:none; margin-top:15px; font-size: 12px; font-style: italic; color: #94a3b8; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                                * Biaya akhir akan ditentukan oleh admin setelah peninjauan.
                            </div>
                        </div>
                        {{-- Decorative circle --}}
                        <div style="position: absolute; right: -20px; bottom: -20px; width: 100px; height: 100px; background: rgba(59, 130, 246, 0.1); border-radius: 50%;"></div>
                    </div>

                    {{-- Errors --}}
                    @if($errors->any())
                        <div style="background: #fff1f2; border: 1px solid #fee2e2; border-radius: 12px; padding: 15px; margin-bottom: 25px; color: #b91c1c; font-size: 14px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div style="display: flex; gap: 12px;">
                        <button type="submit" style="flex: 2; background: #3b82f6; color: white; border: none; padding: 16px; border-radius: 14px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#3b82f6'">
                            Ajukan Permintaan
                        </button>
                        <a href="{{ route('user.services.index') }}" style="flex: 1; text-align: center; text-decoration: none; background: #f1f5f9; color: #475569; padding: 16px; border-radius: 14px; font-weight: 700; font-size: 16px; transition: 0.2s;" onmouseover="this.style.background='#e2e8f0'" onmouseout="this.style.background='#f1f5f9'">
                            Batal
                        </a>
                    </div>
                </form>
            </div>

            {{-- Info Note --}}
            <div style="display: flex; gap: 15px; padding: 20px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 20px;">
                <span style="font-size: 24px;">📝</span>
                <p style="margin: 0; font-size: 13px; color: #92400e; line-height: 1.6;">
                    <strong>Catatan Penting:</strong> Setiap permintaan layanan akan masuk ke tahap antrean. Admin akan melakukan verifikasi ketersediaan petugas atau mitra layanan. Anda akan menerima notifikasi status melalui sistem ini.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Logic scripts tetap sama, hanya memperbarui fungsi formatting visual --}}
@php $grouped = isset($options) ? $options->groupBy('service_type') : collect(); @endphp
<script>
(function(){
    const grouped = @json($grouped);
    const oldServiceType = @json(old('service_type'));
    const oldOptionId = @json(old('service_option_id'));

    const serviceTypeEl = document.getElementById('service_type');
    const optionEl = document.getElementById('service_option_id');
    const qtyWrapper = document.getElementById('qty_wrapper');
    const qtyEl = document.getElementById('quantity');
    const unitNameEl = document.getElementById('unit_name');
    const priceBox = document.getElementById('price_box');
    const unitPriceEl = document.getElementById('unit_price');
    const totalPriceEl = document.getElementById('total_price');
    const quoteNoteEl = document.getElementById('quote_note');

    function formatRupiah(n){
        return 'Rp ' + (n||0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function rebuildOptions(){
        const type = serviceTypeEl.value;
        optionEl.innerHTML = '<option value="">-- Pilih Opsi --</option>';
        const list = grouped[type] || [];
        list.forEach(opt => {
            const o = document.createElement('option');
            o.value = opt.id;
            o.textContent = opt.name;
            o.dataset.pricingType = opt.pricing_type;
            o.dataset.unitName = opt.unit_name || '';
            o.dataset.price = opt.price || '';
            o.dataset.minQty = opt.min_qty || 1;
            o.dataset.maxQty = opt.max_qty || '';
            optionEl.appendChild(o);
        });
        if (oldOptionId) optionEl.value = oldOptionId;
        updatePricing();
    }

    function updatePricing(){
        const sel = optionEl.selectedOptions[0];
        if(!sel || !sel.value){
            qtyWrapper.style.display = 'none';
            priceBox.style.display = 'none';
            return;
        }
        const pricingType = sel.dataset.pricingType;
        const unitName = sel.dataset.unitName || 'unit';
        const unitPrice = sel.dataset.price ? parseInt(sel.dataset.price) : null;
        const minQty = sel.dataset.minQty ? parseInt(sel.dataset.minQty) : 1;

        priceBox.style.display = 'block';
        
        if (pricingType === 'per_unit') {
            qtyWrapper.style.display = 'block';
            unitNameEl.textContent = unitName;
            qtyEl.min = minQty;
            unitPriceEl.textContent = formatRupiah(unitPrice);
            totalPriceEl.textContent = formatRupiah(unitPrice * parseInt(qtyEl.value || 1));
            quoteNoteEl.style.display = 'none';
        } else if (pricingType === 'fixed') {
            qtyWrapper.style.display = 'none';
            unitPriceEl.textContent = formatRupiah(unitPrice);
            totalPriceEl.textContent = formatRupiah(unitPrice);
            quoteNoteEl.style.display = 'none';
        } else {
            qtyWrapper.style.display = 'none';
            unitPriceEl.textContent = '-';
            totalPriceEl.textContent = 'Menunggu Kuotasi';
            quoteNoteEl.style.display = 'block';
        }
    }

    serviceTypeEl.addEventListener('change', rebuildOptions);
    optionEl.addEventListener('change', updatePricing);
    qtyEl && qtyEl.addEventListener('input', updatePricing);
    if (oldServiceType) { serviceTypeEl.value = oldServiceType; rebuildOptions(); }
})();
</script>
@endsection