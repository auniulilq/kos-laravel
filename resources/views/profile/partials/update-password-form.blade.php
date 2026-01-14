<section style="background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 40px; font-family: 'Inter', sans-serif; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
    <header style="margin-bottom: 32px; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px;">
        <h2 style="font-size: 20px; font-weight: 800; color: #1e293b; margin: 0; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 24px;">🔒</span> {{ __('Perbarui Kata Sandi') }}
        </h2>

        <p style="margin-top: 8px; font-size: 14px; color: #64748b; line-height: 1.5;">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak untuk menjaga keamanan tetap optimal.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" style="display: grid; gap: 24px; max-width: 500px;">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <label for="update_password_current_password" style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">
                {{ __('Kata Sandi Saat Ini') }}
            </label>
            <x-text-input 
                id="update_password_current_password" 
                name="current_password" 
                type="password" 
                style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px; background: #f8fafc;" 
                autocomplete="current-password" 
                placeholder="••••••••"
            />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" style="margin-top: 6px; font-size: 13px; color: #e11d48; font-weight: 500;" />
        </div>

        {{-- New Password --}}
        <div>
            <label for="update_password_password" style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">
                {{ __('Kata Sandi Baru') }}
            </label>
            <x-text-input 
                id="update_password_password" 
                name="password" 
                type="password" 
                style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px;" 
                autocomplete="new-password" 
                placeholder="Buat kata sandi baru"
            />
            <x-input-error :messages="$errors->updatePassword->get('password')" style="margin-top: 6px; font-size: 13px; color: #e11d48; font-weight: 500;" />
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="update_password_password_confirmation" style="font-size: 13px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 8px;">
                {{ __('Konfirmasi Kata Sandi') }}
            </label>
            <x-text-input 
                id="update_password_password_confirmation" 
                name="password_confirmation" 
                type="password" 
                style="width: 100%; padding: 12px 16px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 15px;" 
                autocomplete="new-password" 
                placeholder="Ulangi kata sandi baru"
            />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" style="margin-top: 6px; font-size: 13px; color: #e11d48; font-weight: 500;" />
        </div>

        <div style="display: flex; align-items: center; gap: 16px; margin-top: 8px;">
            <button type="submit" style="background: #1e293b; color: white; border: none; padding: 12px 32px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.2s;"
                onmouseover="this.style.background='#000'; this.style.transform='translateY(-1px)'"
                onmouseout="this.style.background='#1e293b'; this.style.transform='translateY(0px)'">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    style="font-size: 14px; color: #10b981; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 6px;"
                >
                    <span style="background: #d1fae5; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px;">✓</span>
                    {{ __('Berhasil diperbarui.') }}
                </p>
            @endif
        </div>
    </form>
</section>