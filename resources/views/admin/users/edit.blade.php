@extends('layouts.app')

@section('title', 'Edit Penyewa')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 25px; font-family: 'Inter', sans-serif; background: #f8fafc; min-height: 100vh;">
    
    {{-- Header --}}
    <div style="margin-bottom: 30px;">
        <a href="{{ route('admin.users.index') }}" style="text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'">
            ← Kembali ke Daftar Penyewa
        </a>
        <h1 style="font-size: 32px; font-weight: 800; color: #1e293b; margin: 12px 0 5px 0; letter-spacing: -1px;">Edit Profil: {{ $user->name }}</h1>
        <p style="color: #64748b; font-size: 15px;">Perbarui informasi identitas atau kelola penempatan unit penyewa.</p>
    </div>

    @if ($errors->any())
        <div style="background: #fee2e2; border: 1px solid #fecaca; color: #b91c1c; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-size: 14px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 25px; align-items: start;">
            
            {{-- Kolom Kiri: Form Identitas --}}
            <div style="display: flex; flex-direction: column; gap: 25px;">
                <div style="background: white; padding: 30px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <h2 style="font-size: 16px; font-weight: 800; color: #1e293b; margin-bottom: 25px; display: flex; align-items: center; gap: 10px;">
                        <span style="background: #eff6ff; padding: 8px; border-radius: 8px; font-size: 14px;">👤</span> Data Pribadi
                    </h2>
                    
                    <div style="display: grid; gap: 20px;">
                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Nama Lengkap <span style="color: #ef4444;">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; transition: 0.3s;" onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';" required>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Email <span style="color: #ef4444;">*</span></label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none;" onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';" required>
                            </div>
                            <div>
                                <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">No. Telepon <span style="color: #ef4444;">*</span></label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none;" onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';" required>
                            </div>
                        </div>

                        <div>
                            <label style="display: block; font-size: 13px; font-weight: 700; color: #475569; margin-bottom: 8px;">Alamat Lengkap <span style="color: #ef4444;">*</span></label>
                            <textarea name="address" rows="3" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; font-family: inherit; transition: 0.3s;" onfocus="this.style.borderColor='#3b82f6'; this.style.background='white';" required>{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>
                </div>

                <div style="background: #f0f9ff; padding: 25px; border-radius: 20px; border: 1px solid #e0f2fe;">
                    <label style="display: flex; align-items: center; gap: 15px; cursor: pointer;">
                        <input type="checkbox" name="whatsapp_opt_in" value="1" {{ old('whatsapp_opt_in', $user->whatsapp_opt_in) ? 'checked' : '' }} 
                               style="width: 20px; height: 20px; accent-color: #0ea5e9;">
                        <div>
                            <span style="display: block; font-size: 14px; font-weight: 700; color: #0369a1;">Aktifkan Notifikasi WhatsApp Otomatis</span>
                            <span style="display: block; font-size: 12px; color: #0ea5e9; opacity: 0.8;">Kirim struk tagihan dan pengingat bayar langsung ke nomor ini.</span>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Kolom Kanan: Keamanan & Kamar --}}
            <div style="display: flex; flex-direction: column; gap: 25px;">
                {{-- Card Kamar Saat Ini --}}
                <div style="background: white; padding: 25px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); position: relative; overflow: hidden;">
                    <div style="position: absolute; right: -10px; top: -10px; font-size: 80px; opacity: 0.05;">🚪</div>
                    <h2 style="font-size: 12px; font-weight: 800; color: #94a3b8; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px;">Unit Kamar Saat Ini</h2>
                    
                    @if($user->room)
                        <div style="display: flex; align-items: center; gap: 15px;">
                            <div style="background: #3b82f6; color: white; width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 800;">
                                {{ $user->room->room_number }}
                            </div>
                            <div>
                                <div style="font-weight: 800; color: #1e293b; font-size: 16px;">Kamar {{ $user->room->room_number }}</div>
                                <div style="font-size: 12px; color: #64748b; font-weight: 600;">{{ strtoupper($user->room->type) }}</div>
                            </div>
                        </div>
                    @else
                        <div style="background: #fff7ed; border: 1px dashed #fdba74; padding: 15px; border-radius: 12px; display: flex; align-items: center; gap: 10px;">
                            <span style="font-size: 18px;">⚠️</span>
                            <span style="color: #c2410c; font-size: 13px; font-weight: 700;">Belum dialokasi ke unit</span>
                        </div>
                    @endif
                </div>

                {{-- Card Password --}}
                <div style="background: white; padding: 25px; border-radius: 24px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <h2 style="font-size: 15px; font-weight: 800; color: #1e293b; margin-bottom: 15px; display: flex; align-items: center; gap: 8px;">
                        <span style="background: #fff7ed; padding: 6px; border-radius: 8px;">🔐</span> Ganti Password
                    </h2>
                    <p style="font-size: 12px; color: #94a3b8; margin-bottom: 15px; line-height: 1.4;">Kosongkan kolom di bawah ini jika tidak ada perubahan password.</p>
                    
                    <div style="display: grid; gap: 12px;">
                        <input type="password" name="password" placeholder="Password Baru" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; font-size: 14px;">
                        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; outline: none; font-size: 14px;">
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 10px;">
                    <button type="submit" style="width: 100%; background: #1e293b; color: white; border: none; padding: 18px; border-radius: 16px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.3s; box-shadow: 0 10px 15px -3px rgba(30, 41, 59, 0.2);" onmouseover="this.style.background='#000'; this.style.transform='translateY(-2px)'" onmouseout="this.style.background='#1e293b'; this.style.transform='translateY(0)'">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.users.index') }}" style="text-align: center; text-decoration: none; color: #64748b; font-size: 14px; font-weight: 700; padding: 12px; transition: 0.2s;" onmouseover="this.style.color='#ef4444'">
                        Batalkan
                    </a>
                </div>
            </div>
        </div>
    </form>

    {{-- Assign Room Section --}}
    @if($emptyRooms->count() > 0)
    <div style="margin-top: 40px; background: white; padding: 30px; border-radius: 24px; border: 1px solid #e2e8f0; position: relative;">
        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 25px;">
            <div style="background: #10b981; color: white; width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px;">🏠</div>
            <div>
                <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;">Pindahkan ke Kamar Lain</h2>
                <p style="color: #64748b; font-size: 13px; margin-top: 2px;">Cari dan alokasikan unit baru yang tersedia.</p>
            </div>
        </div>
        
        <form action="{{ route('admin.users.assignRoom', $user) }}" method="POST" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            @csrf
            @method('PATCH')
            
            <div style="flex: 1; min-width: 250px;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #94a3b8; margin-bottom: 8px; text-transform: uppercase;">Pilih Kamar Tersedia</label>
                <select name="room_id" style="width: 100%; padding: 14px 15px; border-radius: 12px; border: 1.5px solid #e2e8f0; background: #f8fafc; font-size: 14px; font-weight: 600; cursor: pointer; color: #1e293b; outline: none; transition: 0.2s;" onfocus="this.style.borderColor='#10b981'" required>
                    <option value="">-- Cari Kamar Kosong --</option>
                    @foreach($emptyRooms as $room)
                        <option value="{{ $room->id }}" {{ $user->room_id == $room->id ? 'selected' : '' }}>
                            Unit {{ $room->room_number }} - {{ strtoupper($room->type) }} ({{ number_format($room->price, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" style="background: #10b981; color: white; border: none; padding: 16px 30px; border-radius: 12px; font-weight: 700; cursor: pointer; white-space: nowrap; transition: 0.3s; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);" onmouseover="this.style.background='#059669'; this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                Konfirmasi Pindah Kamar
            </button>
        </form>
    </div>
    @endif
</div>
@endsection