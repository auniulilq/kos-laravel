@extends('layouts.app')

@section('title', 'Detail Layanan - #' . $serviceRequest->id)

@section('content')
<div style="max-width: 1100px; margin: 0 auto; padding: 40px 20px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    {{-- Header Navigation --}}
    <div style="margin-bottom: 30px;">
        <a href="{{ route('admin.services.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; display: inline-flex; align-items: center; gap: 8px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="List 10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Log Layanan
        </a>
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 15px;">
            <div>
                <h1 style="font-size: 32px; font-weight: 900; color: #1e293b; margin: 0; letter-spacing: -1px;">Detail Permintaan</h1>
                <p style="color: #94a3b8; margin: 5px 0 0 0; font-weight: 500;">Order ID: <span style="color: #475569; font-weight: 700;">#SERV-{{ str_pad($serviceRequest->id, 5, '0', STR_PAD_LEFT) }}</span></p>
            </div>
            <div style="transform: scale(1.15); transform-origin: right;">
                {!! $serviceRequest->status_badge !!}
            </div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 30px; align-items: start;">
        
        {{-- LEFT COLUMN: Information Details --}}
        <div style="display: flex; flex-direction: column; gap: 25px;">
            
            {{-- Main Info Card --}}
            <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px dashed #e2e8f0;">
                    <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;">Ringkasan Pesanan</h2>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                    <div>
                        <p style="font-size: 11px; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Nama Penyewa</p>
                        <p style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0;">{{ $serviceRequest->user->name }}</p>
                        <p style="font-size: 13px; color: #64748b; margin-top: 2px;">{{ $serviceRequest->user->email }}</p>
                    </div>
                    <div>
                        <p style="font-size: 11px; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Lokasi Unit</p>
                        <div style="display: inline-flex; align-items: center; background: #f1f5f9; padding: 6px 12px; border-radius: 8px; font-weight: 800; color: #2563eb; font-size: 14px;">
                            🏠 Kamar {{ $serviceRequest->room->room_number ?? 'N/A' }}
                        </div>
                    </div>
                    <div>
                        <p style="font-size: 11px; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Tipe Layanan</p>
                        <p style="font-size: 16px; font-weight: 700; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 6px;">
                            {{ $serviceRequest->service_type_name }}
                        </p>
                    </div>
                    <div>
                        <p style="font-size: 11px; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Total Biaya</p>
                        <p style="font-size: 22px; font-weight: 900; color: #10b981; margin: 0;">{{ $serviceRequest->formatted_price ?? '—' }}</p>
                    </div>
                </div>

                @if($serviceRequest->admin_notes)
                <div style="margin-top: 30px; padding: 20px; background: #f8fafc; border-radius: 16px; border-left: 4px solid #3b82f6;">
                    <p style="font-size: 11px; color: #3b82f6; font-weight: 800; margin: 0 0 8px 0; text-transform: uppercase;">Memo Internal:</p>
                    <p style="font-size: 14px; color: #475569; margin: 0; line-height: 1.6; font-style: italic;">"{{ $serviceRequest->admin_notes }}"</p>
                </div>
                @endif
            </div>

            {{-- Linked Invoice Card --}}
            @if($serviceRequest->payment)
            <div style="background: linear-gradient(to right, #ecfdf5, #f0fdf4); padding: 25px; border-radius: 24px; border: 1px solid #dcfce7; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.05);">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <div style="background: white; width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">💳</div>
                    <div>
                        <p style="font-size: 11px; color: #166534; font-weight: 800; margin: 0; text-transform: uppercase;">Tagihan Terbit</p>
                        <p style="font-size: 16px; color: #064e3b; font-weight: 800; margin: 2px 0;">{{ $serviceRequest->payment->invoice_number }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.payments.show', $serviceRequest->payment) }}" style="background: #10b981; color: white; padding: 12px 20px; border-radius: 12px; text-decoration: none; font-size: 13px; font-weight: 700; transition: 0.2s; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);" onmouseover="this.style.background='#059669'">
                    Lihat Invoice
                </a>
            </div>
            @endif
        </div>

        {{-- RIGHT COLUMN: Admin Actions --}}
        <div style="position: sticky; top: 30px; display: flex; flex-direction: column; gap: 20px;">
            
            {{-- Decision Panel (Pending Only) --}}
            @if($serviceRequest->status === 'pending')
            <div style="background: white; padding: 30px; border-radius: 24px; border: 1.5px solid #3b82f6; box-shadow: 0 20px 25px -5px rgba(59, 130, 246, 0.1);">
                <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 25px;">Tindakan Diperlukan</h2>
                
                <form action="{{ route('admin.services.approve', $serviceRequest) }}" method="POST">
                    @csrf @method('PATCH')
                    <div style="margin-bottom: 20px;">
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; margin-bottom: 8px; text-transform: uppercase;">Final Harga (IDR)</label>
                        <div style="position: relative;">
                            <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); font-weight: 700; color: #94a3b8;">Rp</span>
                            <input type="number" name="price" placeholder="0" style="width: 100%; padding: 14px 15px 14px 45px; border-radius: 12px; border: 1.5px solid #e2e8f0; outline: none; font-size: 16px; font-weight: 700; color: #1e293b; transition: 0.2s;" onfocus="this.style.borderColor='#3b82f6'" required>
                        </div>
                    </div>
                    
                    <div style="margin-bottom: 25px;">
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; margin-bottom: 8px; text-transform: uppercase;">Catatan untuk Penyewa</label>
                        <textarea name="admin_notes" rows="3" placeholder="Contoh: Estimasi selesai besok pagi..." style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #e2e8f0; outline: none; font-size: 14px; font-family: inherit; transition: 0.2s;" onfocus="this.style.borderColor='#3b82f6'"></textarea>
                    </div>
                    
                    <button type="submit" style="width: 100%; background: #1e293b; color: white; border: none; padding: 16px; border-radius: 14px; font-weight: 700; cursor: pointer; font-size: 15px; transition: 0.2s; margin-bottom: 12px;" onmouseover="this.style.background='#0f172a'">
                        Konfirmasi & Setujui
                    </button>
                </form>

                <div x-data="{ open: false }">
                    <button @click="open = !open" type="button" style="width: 100%; background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3; padding: 14px; border-radius: 14px; font-weight: 700; cursor: pointer; font-size: 14px; transition: 0.2s;" onmouseover="this.style.background='#ffe4e6'">
                        Tolak Permintaan
                    </button>
                    
                    <form action="{{ route('admin.services.reject', $serviceRequest) }}" method="POST" style="display: none; margin-top: 15px;" id="rejectForm">
                        @csrf @method('PATCH')
                        <textarea name="admin_notes" placeholder="Berikan alasan penolakan..." style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid #fb7185; margin-bottom: 10px; font-size: 14px;" required></textarea>
                        <button type="submit" style="width: 100%; background: #e11d48; color: white; border: none; padding: 12px; border-radius: 12px; font-weight: 700;">Ya, Tolak Sekarang</button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Progression Panel (Approved Only) --}}
            @if($serviceRequest->status === 'approved')
            <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #e2e8f0; text-align: center;">
                <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 10px;">Dalam Pengerjaan</h2>
                <p style="font-size: 14px; color: #64748b; margin-bottom: 25px;">Klik tombol di bawah jika pekerjaan layanan telah selesai dilakukan.</p>
                
                <form action="{{ route('admin.services.complete', $serviceRequest) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" style="width: 100%; background: #3b82f6; color: white; border: none; padding: 16px; border-radius: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px; font-size: 15px; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.25);">
                       Selesaikan Layanan
                    </button>
                </form>
            </div>
            @endif

            {{-- Communication Panel --}}
            <div style="background: #ffffff; padding: 20px; border-radius: 24px; border: 1px solid #e2e8f0;">
                <p style="font-size: 11px; color: #94a3b8; font-weight: 800; text-transform: uppercase; margin-bottom: 15px; text-align: center; letter-spacing: 1px;">Komunikasi Penyewa</p>
                <form action="{{ route('admin.services.sendWaUpdate', $serviceRequest) }}" method="POST">
                    @csrf
                    <button type="submit" style="width: 100%; background: #25d366; color: white; border: none; padding: 14px; border-radius: 14px; font-weight: 700; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 5px; transition: 0.2s;" onmouseover="this.style.transform='scale(1.02)'">
                        <svg width="50" height="50" fill="currentColor" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.771-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.299.045-.677.063-1.092-.069-.252-.08-.575-.187-.988-.365-1.739-.747-2.874-2.512-2.96-2.626-.087-.115-.708-.942-.708-1.797 0-.855.448-1.274.607-1.446.159-.172.346-.215.46-.215.115 0 .23 0 .331.005.106.005.249-.04.39.298.144.347.491 1.2.534 1.287.043.086.072.187.014.302-.058.115-.087.187-.173.287-.086.1-.181.224-.259.302-.086.086-.176.181-.076.353.1.172.443.731.951 1.183.654.582 1.206.763 1.379.849.172.086.273.072.374-.043.101-.115.432-.504.548-.677.115-.172.23-.144.389-.086.158.058 1.008.475 1.181.562.172.086.287.129.331.201.044.072.044.417-.1.822z"/></svg>
                        WhatsApp Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple toggle for rejection form without heavy libraries
    document.querySelector('button[onclick]').onclick = function() {
        const form = document.getElementById('rejectForm');
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
        this.style.display = 'none';
    };
</script>
@endsection