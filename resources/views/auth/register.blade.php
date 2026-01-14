<x-guest-layout>
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="font-size: 24px; font-weight: 800; color: #1e293b; margin-bottom: 8px;">Register</h2>
        
    </div>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label>Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Masukkan nama sesuai KTP">

        <label>Email Address</label>
        <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com">

        <label>Nomor Telepon/WA</label>
        <input type="text" name="phone" value="{{ old('phone') }}" required placeholder="0812xxxxxxxx">

        <label>Alamat Asal</label>
        <input type="text" name="address" required placeholder="Alamat lengkap">

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
            <div>
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <div>
                <label>Konfirmasi</label>
                <input type="password" name="password_confirmation" required>
            </div>
        </div>

        <button type="submit">DAFTAR SEKARANG →</button>

        <div class="auth-footer">
            Sudah punya akun? <a href="{{ route('login') }}" class="auth-link">Login di sini</a>
        </div>
    </form>
</x-guest-layout>