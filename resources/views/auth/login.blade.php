<x-guest-layout>
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-size: 24px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Login</h2>
        <p style="color: #64748b; font-size: 14px;">Silakan masuk untuk mengelola kos Anda</p>
    </div>

    @if (session('status'))
        <div style="background: #f0fdf4; color: #166534; padding: 12px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background: #fef2f2; color: #991b1b; padding: 12px; border-radius: 12px; font-size: 13px; margin-bottom: 20px; border: 1px solid #fee2e2;">
            <ul style="margin: 0; padding-left: 15px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div style="margin-bottom: 15px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 8px;">Email Address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; margin-bottom: 8px;">Password</label>
            <input type="password" name="password" required placeholder="••••••••" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; font-size: 14px;">
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: #64748b; cursor: pointer;">
                <input type="checkbox" name="remember" style="width: 16px; height: 16px; border-radius: 4px; border: 1px solid #cbd5e1;">
                Ingat saya
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size: 13px; color: #3b82f6; text-decoration: none; font-weight: 600;">Lupa Password?</a>
            @endif
        </div>

        <button type="submit" style="width: 100%; background: #1e293b; color: white; border: none; padding: 14px; border-radius: 12px; font-weight: 700; font-size: 15px; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#000'" onmouseout="this.style.background='#1e293b'">
            Masuk Sekarang →
        </button>

        <div style="text-align: center; margin-top: 25px; font-size: 14px; color: #64748b;">
            Belum punya akun? <a href="{{ route('register') }}" style="color: #3b82f6; text-decoration: none; font-weight: 700;">Daftar di sini</a>
        </div>

        <!-- <div style="margin-top: 30px; padding: 15px; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1;">
            <p style="font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.5px;">Mode Testing:</p>
            <div style="font-size: 12px; color: #475569; line-height: 1.6;">
                <code style="background: #e2e8f0; padding: 2px 4px; border-radius: 4px;">Admin:</code> admin@kos.com /admin123<br>
                <code style="background: #e2e8f0; padding: 2px 4px; border-radius: 4px;">User :</code> budi@gmail.com /password
            </div>
        </div> -->
    </form>
</x-guest-layout>