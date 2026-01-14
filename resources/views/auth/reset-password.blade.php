<x-guest-layout>
    <div class="brutalist-container">
        <div class="brutalist-title">
            RESET PASSWORD
        </div>

        @if ($errors->any())
            <div class="brutalist-error">
                <div style="font-weight: bold; margin-bottom: 5px;">WADUH! ADA MASALAH:</div>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="form-group">
                <label for="email" class="brutalist-label">
                    EMAIL ADDRESS:
                </label>
                <input 
                    id="email" 
                    class="brutalist-input" 
                    type="email" 
                    name="email" 
                    value="{{ old('email', $request->email) }}" 
                    required 
                    autofocus 
                    placeholder="nama@email.com"
                >
            </div>

            <div class="form-group">
                <label for="password" class="brutalist-label">
                    PASSWORD BARU:
                </label>
                <input 
                    id="password" 
                    class="brutalist-input" 
                    type="password" 
                    name="password" 
                    required 
                    placeholder="Minimal 8 karakter"
                >
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="brutalist-label">
                    KONFIRMASI PASSWORD:
                </label>
                <input 
                    id="password_confirmation" 
                    class="brutalist-input" 
                    type="password" 
                    name="password_confirmation" 
                    required 
                    placeholder="Ketik ulang password baru"
                >
            </div>

            <button type="submit" class="brutalist-button" style="background: #00FF00;">
                SIMPAN PASSWORD BARU →
            </button>
        </form>
    </div>
</x-guest-layout>