@extends('layouts.app')

@section('title', 'Tambah Penyewa Baru')

@section('content')
<div style="max-width: 900px; margin: 0 auto; padding: 25px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    {{-- Header --}}
    <div style="margin-bottom: 30px;">
        <a href="{{ route('admin.users.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
            ← Kembali ke Daftar Penyewa
        </a>
        <h1 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 12px 0 5px 0; letter-spacing: -1px;">Registrasi Penyewa Baru</h1>
        <p style="color: #64748b; font-size: 15px;">Daftarkan akun penyewa dan alokasikan unit kamar dalam satu langkah.</p>
    </div>

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-size: 14px;">
            <strong style="display: block; margin-bottom: 5px;">Mohon perbaiki kesalahan berikut:</strong>
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div style="display: flex; flex-direction: column; gap: 25px;">
            
            {{-- Section 1: Profil Dasar --}}
            <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                <h2 style="font-size: 16px; font-weight: 800; color: #1e293b; margin-bottom: 25px; display: flex; align-items: center; gap: 12px;">
                    <span style="background: #eff6ff; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; font-size: 14px;">👤</span> 
                    Informasi Personal
                </h2>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="name" placeholder="Masukkan nama lengkap penyewa" value="{{ old('name') }}" 
                            style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; transition: 0.3s; font-size: 14px;" onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Alamat Email <span style="color: #ef4444;">*</span></label>
                        <input type="email" name="email" placeholder="contoh@email.com" value="{{ old('email') }}" 
                            style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; transition: 0.3s; font-size: 14px;" onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';" required>
                    </div>

                    <div>
                        <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Nomor HP / WhatsApp <span style="color: #ef4444;">*</span></label>
                        <input type="text" name="phone" placeholder="08xxxxxxxxxx" value="{{ old('phone') }}" 
                            style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; transition: 0.3s; font-size: 14px;" onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';" required>
                    </div>
                </div>
            </div>

            {{-- Section 2: Keamanan & Penempatan --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
                {{-- Keamanan --}}
                <div style="background: white; padding: 25px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <h2 style="font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: #fff7ed; padding: 6px; border-radius: 8px;">🔐</span> Keamanan Akun
                    </h2>
                    <div style="display: grid; gap: 15px;">
                        <input type="password" name="password" placeholder="Buat Password Baru" 
                            style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; font-size: 14px;" onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';" required>
                        <input type="password" name="password_confirmation" placeholder="Ulangi Password" 
                            style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; font-size: 14px;" onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';" required>
                    </div>
                </div>

                {{-- Kamar --}}
                <div style="background: white; padding: 25px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <h2 style="font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: #f0fdf4; padding: 6px; border-radius: 8px;">🚪</span> Alokasi Unit
                    </h2>
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Unit Kamar Tersedia</label>
                    <select name="room_id" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; cursor: pointer; font-size: 14px; font-weight: 600; color: #1e293b;">
                        <option value="">-- Lewati dulu --</option>
                        @foreach($emptyRooms as $room)
                            <option value="{{ $room->id }}">
                                Kamar {{ $room->room_number }} ({{ $room->type }})
                            </option>
                        @endforeach
                    </select>
                    <p style="font-size: 12px; color: #94a3b8; margin-top: 12px; line-height: 1.4;">Hanya menampilkan unit dengan status <b>Kosong</b>.</p>
                </div>
            </div>

            {{-- Section 3: Alamat --}}
            <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Alamat Asal (Sesuai KTP) <span style="color: #ef4444;">*</span></label>
                    <textarea name="address" rows="3" placeholder="Tuliskan alamat lengkap..." 
                        style="width: 100%; padding: 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; font-family: inherit; font-size: 14px; transition: 0.3s;" onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';" required>{{ old('address') }}</textarea>
                </div>

                <div style="background: #f0fdf4; padding: 18px; border-radius: 16px; border: 1px solid #dcfce7;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="whatsapp_opt_in" value="1" {{ old('whatsapp_opt_in', true) ? 'checked' : '' }} 
                               style="width: 20px; height: 20px; accent-color: #22c55e;">
                        <div>
                            <span style="font-size: 14px; font-weight: 700; color: #166534; display: block;">Aktifkan Notifikasi WhatsApp Otomatis</span>
                            <span style="font-size: 12px; color: #15803d; opacity: 0.8;">Kirim struk tagihan dan pengingat bayar langsung ke WA penyewa.</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Footer Buttons --}}
            <div style="display: flex; gap: 15px; margin-top: 10px; margin-bottom: 50px;">
                <button type="submit" style="flex: 2; background: #1e293b; color: white; border: none; padding: 18px; border-radius: 16px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.2);" onmouseover="this.style.background='#000'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#1e293b'; this.style.transform='translateY(0)'">
                    Simpan & Daftarkan Penyewa
                </button>
                <a href="{{ route('admin.users.index') }}" style="flex: 1; text-align: center; text-decoration: none; background: white; color: #64748b; padding: 18px; border-radius: 16px; font-weight: 700; font-size: 16px; transition: 0.2s; border: 1px solid #e2e8f0;" onmouseover="this.style.background='#f8fafc'">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection