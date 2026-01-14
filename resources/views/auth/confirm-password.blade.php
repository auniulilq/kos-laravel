<x-guest-layout>
    <div class="brutalist-container">
        <div class="brutalist-title">
            KONFIRMASI
        </div>

        <div class="brutalist-info-box" style="background: #e0e0e0; margin-bottom: 20px;">
            {{ __('Ini adalah area aman. Silakan masukkan password Anda kembali untuk melanjutkan.') }}
        </div>

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div class="form-group">
                <label for="password" class="brutalist-label">
                    PASSWORD ANDA:
                </label>
                <input 
                    id="password" 
                    class="brutalist-input"
                    type="password"
                    name="password"
                    required 
                    autocomplete="current-password"
                    placeholder="••••••••"
                    autofocus
                >
                
                @if ($errors->has('password'))
                    <div class="brutalist-error" style="margin-top: 10px; padding: 5px 10px;">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <div style="display: flex; justify-content: flex-end; mt-4;">
                <button type="submit" class="brutalist-button" style="width: auto; padding: 10px 30px;">
                    KONFIRMASI →
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>