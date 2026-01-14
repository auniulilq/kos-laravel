<x-guest-layout>
    <div class="brutalist-title">
        FORGOT PASSWORD
    </div>

    <div style="margin-bottom: 20px; padding: 15px; border: 3px solid #000000; background: #E5F5FF;">
        <strong>Forgot your password?</strong> No problem. Just let us know your email address and we will email you a password reset link.
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="brutalist-error" style="background: #00FF00; color: #000000;">
            {{ session('status') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="brutalist-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="brutalist-label">
                Email Address:
            </label>
            <input 
                id="email" 
                class="brutalist-input" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus
                placeholder="your@email.com"
            >
        </div>

        <button type="submit" class="brutalist-button" style="background: #0000FF; color: #FFFFFF;">
            SEND RESET LINK →
        </button>

        <div class="brutalist-divider"></div>

        <div style="text-align: center;">
            <a class="brutalist-link" href="{{ route('login') }}">
                ← BACK TO LOGIN
            </a>
        </div>
    </form>
</x-guest-layout>