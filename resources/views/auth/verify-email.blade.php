<x-guest-layout>
    <div class="brutalist-container">
        <div class="brutalist-title">
            VERIFIKASI EMAIL
        </div>

        <div class="brutalist-info-box" style="background: #FFF5CC; margin-bottom: 20px;">
            {{ __('Terima kasih sudah mendaftar! Sebelum mulai, tolong verifikasi email kamu dengan klik link yang baru saja kami kirimkan. Belum terima email? Kami akan kirim ulang.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="brutalist-success" style="margin-bottom: 20px; padding: 10px; background: #00FF00; font-weight: bold; border: 3px solid #000;">
                {{ __('Link verifikasi baru sudah dikirim ke alamat email yang kamu daftarkan.') }}
            </div>
        @endif

        <div class="mt-4" style="display: flex; flex-direction: column; gap: 15px;">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="brutalist-button" style="background: #00FF00;">
                    KIRIM ULANG EMAIL VERIFIKASI →
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" style="text-align: center;">
                @csrf
                <button type="submit" class="brutalist-link" style="background: none; border: none; cursor: pointer; text-decoration: underline; font-weight: bold;">
                    LOG OUT (KELUAR)
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>