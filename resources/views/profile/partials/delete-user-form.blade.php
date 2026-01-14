<section style="background: white; border: 1px solid #fee2e2; border-radius: 20px; overflow: hidden; font-family: 'Inter', sans-serif;">
    {{-- Header Area dengan Background Halus Merah --}}
    <header style="background: #fff1f2; padding: 25px 30px; border-bottom: 1px solid #fecdd3;">
        <h2 style="font-size: 18px; font-weight: 800; color: #991b1b; margin: 0; display: flex; align-items: center; gap: 10px;">
            <span style="font-size: 22px;">⚠️</span> {{ __('Hapus Akun') }}
        </h2>

        <p style="margin-top: 8px; font-size: 14px; color: #b91c1c; font-weight: 500; line-height: 1.5;">
            {{ __('Tindakan ini permanen. Setelah akun dihapus, semua data dan sumber daya Anda akan dihapus selamanya. Harap unduh data penting sebelum melanjutkan.') }}
        </p>
    </header>

    <div style="padding: 30px;">
        {{-- Tombol Pemicu Utama --}}
        <button 
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            style="background: #e11d48; color: white; border: none; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 6px -1px rgba(225, 29, 72, 0.2);"
            onmouseover="this.style.background='#be123c'; this.style.transform='translateY(-1px)'"
            onmouseout="this.style.background='#e11d48'; this.style.transform='translateY(0px)'"
        >
            {{ __('Hapus Akun Saya') }}
        </button>
    </div>

    {{-- Modal Konfirmasi --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" style="padding: 40px; background: white; border-radius: 24px;">
            @csrf
            @method('delete')

            <div style="text-align: center; margin-bottom: 30px;">
                <div style="width: 60px; height: 60px; background: #fff1f2; color: #e11d48; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; margin: 0 auto 20px;">
                    ❗
                </div>
                <h2 style="font-size: 22px; font-weight: 800; color: #1e293b; margin: 0;">
                    {{ __('Apakah Anda benar-benar yakin?') }}
                </h2>
                <p style="margin-top: 12px; font-size: 14px; color: #64748b; line-height: 1.6;">
                    {{ __('Tolong masukkan kata sandi Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen. Tindakan ini tidak dapat dibatalkan.') }}
                </p>
            </div>

            <div style="margin-top: 25px;">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    style="width: 100%; padding: 14px; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 16px; transition: 0.2s;"
                    placeholder="{{ __('Masukkan Kata Sandi Konfirmasi') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" style="margin-top: 8px; font-size: 13px; color: #e11d48; font-weight: 600;" />
            </div>

            <div style="margin-top: 40px; display: flex; gap: 12px; justify-content: center;">
                <button 
                    type="button"
                    x-on:click="$dispatch('close')"
                    style="flex: 1; background: #f1f5f9; color: #475569; border: none; padding: 14px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.2s;"
                    onmouseover="this.style.background='#e2e8f0'"
                    onmouseout="this.style.background='#f1f5f9'"
                >
                    {{ __('Batalkan') }}
                </button>

                <button 
                    type="submit"
                    style="flex: 1; background: #1e293b; color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; cursor: pointer; transition: 0.2s;"
                    onmouseover="this.style.background='#000'"
                    onmouseout="this.style.background='#1e293b'"
                >
                    {{ __('Ya, Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>