@extends('layouts.app')

@section('content')
<div class="brutalist-container">
    <div class="brutalist-grid">
        <div class="brutalist-card">
            <h2 class="section-title">Tes Notifikasi WhatsApp</h2>
            <p>Kirim pesan ke nomor tertentu untuk memastikan integrasi berjalan.</p>

            @if(session('success'))
                <div class="brutalist-badge" style="background:#10b981; color:#fff;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="brutalist-badge" style="background:#ef4444; color:#fff;">{{ session('error') }}</div>
            @endif
            @if(session('debug'))
                <pre style="margin-top:8px; padding:10px; border:2px solid #000; background:#f8fafc; max-width:640px; white-space:pre-wrap;">{{ session('debug') }}</pre>
            @endif
            @if($errors->any())
                <div class="brutalist-badge" style="background:#ef4444; color:#fff;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.notifications.test.send') }}" style="margin-top:12px;">
                @csrf
                <div style="display:grid; gap:10px; grid-template-columns:1fr; max-width:480px;">
                    <label>
                        <div class="brutalist-badge" style="background:#000; color:#fff;">Nomor WhatsApp</div>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="Contoh: 08xxxxxxxxxx" class="brutalist-input" style="width:100%; padding:10px; border:2px solid #000;" />
                    </label>
                    <label>
                        <div class="brutalist-badge" style="background:#000; color:#fff;">Pesan</div>
                        <textarea name="message" rows="4" placeholder="Tulis pesan yang ingin dikirim" class="brutalist-input" style="width:100%; padding:10px; border:2px solid #000;">{{ old('message', 'Halo! Ini pesan uji dari Kos Management.') }}</textarea>
                    </label>
                    <button type="submit" class="brutalist-btn" style="align-self:start;">Kirim</button>
                </div>
            </form>

            <div style="margin-top:16px;">
                <div class="brutalist-badge">Catatan</div>
                <ul style="margin-top:8px;">
                    <li>Pastikan <code>FONNTE_API_KEY</code> sudah diisi di file <code>.env</code>.</li>
                    <li>Nomor akan otomatis diubah ke format Indonesia (62xxxxxxxxxx).</li>
                    <li>Lihat log di <code>storage/logs/laravel.log</code> jika terjadi kesalahan.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection