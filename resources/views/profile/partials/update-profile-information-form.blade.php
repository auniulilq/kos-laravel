<section style="background: white; border: 1px solid #e2e8f0; border-radius: 24px; padding: 40px; font-family: 'Inter', sans-serif; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
    <header style="margin-bottom: 32px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 800; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 24px;">👤</span> {{ __('Informasi Profil') }}
        </h2>

        <p style="margin-top: 8px; font-size: 14px; color: #64748b; line-height: 1.5;">
            {{ __("Perbarui informasi profil akun dan alamat email Anda untuk memastikan data tetap akurat.") }}
        </p>
    </header>

    {{-- Form Verifikasi Email (Hidden) --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" style="display: grid; gap: 24px; max-width: 600px;">
        @csrf
        @method('patch')

        {{-- Name Input --}}
        <div>
            <label for="name" style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">
                {{ __('Nama Lengkap') }}
            </label>
            <x-text-input id="name" name="name" type="text" style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px;" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error style="margin-top: 6px; font-size: 13px; color: #e11d48;" :messages="$errors->get('name')" />
        </div>

        {{-- Email Input --}}
        <div>
            <label for="email" style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">
                {{ __('Alamat Email') }}
            </label>
            <x-text-input id="email" name="email" type="email" style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px;" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error style="margin-top: 6px; font-size: 13px; color: #e11d48;" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top: 12px; padding: 12px; background: #fffbeb; border-radius: 10px; border: 1px solid #fef3c7;">
                    <p style="font-size: 13px; color: #92400e; margin: 0;">
                        {{ __('Email Anda belum terverifikasi.') }}
                        <button form="send-verification" style="background: none; border: none; color: #b45309; text-decoration: underline; font-weight: 700; cursor: pointer; padding: 0;">
                            {{ __('Klik di sini untuk kirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p style="margin-top: 8px; font-size: 13px; color: #166534; font-weight: 600;">
                            {{ __('Link verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Phone Input --}}
        <div>
            <label for="phone" style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">
                {{ __('Nomor WhatsApp/Telepon') }}
            </label>
            <div style="position: relative;">
                <span style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-weight: 600;">+62</span>
                <x-text-input id="phone" name="phone" type="text" style="width: 100%; padding: 12px 16px 12px 50px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px;" :value="old('phone', $user->phone)" autocomplete="tel" placeholder="812xxxx" />
            </div>
            <x-input-error style="margin-top: 6px; font-size: 13px; color: #e11d48;" :messages="$errors->get('phone')" />
        </div>

        {{-- WhatsApp Opt-in --}}
        <div style="background: #f0fdf4; padding: 16px; border-radius: 12px; border: 1px solid #dcfce7; display: flex; align-items: flex-start; gap: 12px;">
            <input id="whatsapp_opt_in" name="whatsapp_opt_in" type="checkbox" value="1" style="width: 18px; height: 18px; cursor: pointer; margin-top: 2px;" @checked(old('whatsapp_opt_in', $user->whatsapp_opt_in)) />
            <div>
                <label for="whatsapp_opt_in" style="font-size: 14px; font-weight: 700; color: #166534; cursor: pointer; display: block;">
                    {{ __('Aktifkan Notifikasi WhatsApp') }}
                </label>
                <p style="font-size: 12px; color: #15803d; margin-top: 4px; line-height: 1.4;">
                    Dapatkan info tagihan, konfirmasi pembayaran, dan pengumuman kos langsung ke WhatsApp Anda.
                </p>
            </div>
        </div>

        <div style="display: flex; align-items: center; gap: 16px; margin-top: 8px;">
            <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 12px 32px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.2s; box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.2);"
                onmouseover="this.style.background='#2563eb'; this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='#3b82f6'; this.style.transform='translateY(0px)'">
                {{ __('Simpan Profil') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    style="font-size: 14px; color: #10b981; font-weight: 700; margin: 0;"
                >
                    ✨ {{ __('Berhasil disimpan.') }}
                </p>
            @endif
        </div>
    </form>
</section>