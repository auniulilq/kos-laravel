@extends('layouts.app')

@section('title', 'Detail Kamar ' . $room->room_number)

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Header Navigation --}}
        <div style="margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between;">
            <a href="{{ route('home') }}" style="text-decoration: none; color: #64748b; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Kamar
            </a>
            <div style="font-size: 14px; color: #94a3b8;">
                Home / Kamar / <span style="color: #1e293b; font-weight: 600;">{{ $room->room_number }}</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1.8fr 1fr; gap: 32px; align-items: start;">
            
            {{-- LEFT COLUMN: Content --}}
            <div class="left-column">
                {{-- Gallery Card --}}
                <div style="background: white; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04); margin-bottom: 32px; border: 1px solid #e2e8f0;">
                    <div style="position: relative; height: 450px;">
                        <img src="{{ $room->image ? asset('storage/' . $room->image) : 'https://picsum.photos/seed/room-'.$room->id.'/1200/800' }}" 
                             alt="Foto Kamar {{ $room->room_number }}" 
                             style="width: 100%; height: 100%; object-fit: cover;">
                        
                        <div style="position: absolute; top: 20px; left: 20px; display: flex; gap: 10px;">
                            <span style="background: rgba(255,255,255,0.9); backdrop-filter: blur(4px); padding: 8px 16px; border-radius: 12px; font-weight: 800; font-size: 12px; color: #1e293b; text-transform: uppercase; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                                Kategori: {{ strtoupper($room->category->name ?? 'Tanpa Kategori') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Info & Facilities --}}
                <div style="background: white; border-radius: 24px; padding: 40px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
                    <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 24px;">Detail & Fasilitas Kamar</h1>
                    
                    <p style="color: #64748b; line-height: 1.7; margin-bottom: 32px;">
                        {{ $room->description ?? 'Kamar yang nyaman dengan pencahayaan yang baik, cocok untuk pelajar atau pekerja profesional yang menginginkan ketenangan.' }}
                    </p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                        <div>
                            <h3 style="font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">Spesifikasi Utama</h3>
                            <ul style="list-style: none; padding: 0; display: grid; gap: 12px;">
    {{-- Ukuran Kamar --}}
    <li style="display: flex; align-items: center; gap: 12px; color: #334155; font-weight: 500;">
        <span style="background: #f1f5f9; padding: 8px; border-radius: 10px;">📐</span> 
        Ukuran {{ $room->area ?? '3 x 4' }} Meter
    </li>

    {{-- Listrik --}}
    <li style="display: flex; align-items: center; gap: 12px; color: #334155; font-weight: 500;">
        <span style="background: #f1f5f9; padding: 8px; border-radius: 10px;">⚡</span> 
        Listrik {{ $room->electricity ?? 'Token (Mandiri)' }}
    </li>

    {{-- Kapasitas --}}
    <li style="display: flex; align-items: center; gap: 12px; color: #334155; font-weight: 500;">
        <span style="background: #f1f5f9; padding: 8px; border-radius: 10px;">👥</span> 
        Maks. {{ $room->capacity ?? '2' }} Orang
    </li>
</ul>
                        </div>
                        
                        <div>
    <h3 style="font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">Fasilitas Tersedia</h3>
    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
       {{-- Bagian Fasilitas Tersedia --}}
@php 
    // Pastikan data adalah array, jika string JSON maka decode
    $roomFacilityIds = is_array($room->facilities) ? $room->facilities : (json_decode($room->facilities, true) ?? []); 
    
    $activeFacilities = \App\Models\Facility::whereIn('id', $roomFacilityIds)->get();
@endphp
        
        @forelse($activeFacilities as $fac)
            <span style="background: #eff6ff; color: #2563eb; padding: 6px 14px; border-radius: 30px; font-size: 13px; font-weight: 600; border: 1px solid #dbeafe;">
                {{ $fac->name }} {{-- Sekarang memanggil NAMA, bukan ID --}}
            </span>
        @empty
            <span style="color: #94a3b8; font-style: italic;">Fasilitas standar tersedia</span>
        @endforelse
    </div>
</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Booking Card --}}
            <div class="right-column" x-data="{ 
                durasi: 'Bulanan', 
                showDetails: true,
                selectedDate: '',
                basePrice: {{ $room->price }},
                get prices() {
                    return {
                        'Mingguan': Math.round(this.basePrice / 3),
                        'Bulanan': this.basePrice,
                        'Tahunan': this.basePrice * 11
                    }
                },
                get checkoutDate() {
                    if(!this.selectedDate) return '-';
                    let start = new Date(this.selectedDate);
                    if(this.durasi === 'Mingguan') start.setDate(start.getDate() + 7);
                    else if(this.durasi === 'Bulanan') start.setMonth(start.getMonth() + 1);
                    else if(this.durasi === 'Tahunan') start.setFullYear(start.getFullYear() + 1);
                    return start.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                },
                get hargaSekarang() { return this.prices[this.durasi] },
                get dp() { return (this.hargaSekarang * 0.3).toLocaleString('id-ID') },
                get total() { return (this.hargaSekarang).toLocaleString('id-ID') }
            }">
                <div style="background: white; border-radius: 28px; padding: 32px; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05); position: sticky; top: 40px;">
                    
                    {{-- Price Section --}}
                    <div style="margin-bottom: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span style="padding: 6px 12px; border-radius: 30px; font-size: 12px; font-weight: 700; background: {{ $room->status === 'empty' ? '#dcfce7' : '#fee2e2' }}; color: {{ $room->status === 'empty' ? '#15803d' : '#991b1b' }};">
                                {{ $room->status === 'empty' ? 'Tersedia' : 'Terisi' }}
                            </span>
                            <span style="color: #64748b; font-size: 14px;">Mulai dari</span>
                        </div>
                        <div style="font-size: 32px; font-weight: 900; color: #1e293b;">
                            Rp<span x-text="hargaSekarang.toLocaleString('id-ID')"></span>
                            <span style="font-size: 14px; font-weight: 500; color: #94a3b8;" x-text="'/' + durasi.toLowerCase().replace('an', '')"></span>
                        </div>
                    </div>

                    {{-- Form Section --}}
                    <div style="display: grid; gap: 20px; border-top: 1px solid #f1f5f9; padding-top: 24px; margin-bottom: 24px;">
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 8px;">Tanggal Mulai Kos</label>
                            <input type="date" x-model="selectedDate" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; font-family: 'Inter';">
                        </div>
                        
                        <div>
                            <label style="display: block; font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 8px;">Durasi Sewa</label>
                            <select x-model="durasi" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; background: white; cursor: pointer;">
                                <option value="Mingguan">Per Minggu</option>
                                <option value="Bulanan">Per Bulan</option>
                                <option value="Tahunan">Per Tahun (Diskon 1 Bln)</option>
                            </select>
                        </div>
                    </div>

                    {{-- Summary Box --}}
                    <div x-show="selectedDate" style="background: #f8fafc; border-radius: 16px; padding: 20px; margin-bottom: 24px; border: 1px solid #f1f5f9;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                            <span style="color: #64748b;">Masa sewa berakhir:</span>
                            <span style="font-weight: 700; color: #1e293b;" x-text="checkoutDate"></span>
                        </div>
                        <div style="display: flex; justify-content: space-between; font-size: 14px; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
                            <span style="color: #64748b;">Booking Fee (DP 30%):</span>
                            <span style="font-weight: 700; color: #2563eb;">Rp<span x-text="dp"></span></span>
                        </div>
                    </div>

                    {{-- Action Button --}}
                    @auth
                        @if($room->status === 'empty')
                            <form action="{{ route('bookings.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                                <input type="hidden" :value="durasi" name="duration">
                                <input type="hidden" :value="selectedDate" name="start_date">
                                
                                <button type="submit" 
                                        style="width: 100%; background: #2563eb; color: white; border: none; padding: 18px; border-radius: 16px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.2s; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);"
                                        onmouseover="this.style.background='#1d4ed8'; this.style.transform='translateY(-2px)'"
                                        onmouseout="this.style.background='#2563eb'; this.style.transform='translateY(0)'"
                                        :disabled="!selectedDate">
                                    Ajukan Sewa Sekarang
                                </button>
                            </form>
                        @else
                            <button disabled style="width: 100%; background: #94a3b8; color: white; border: none; padding: 18px; border-radius: 16px; font-weight: 700; cursor: not-allowed;">
                                Kamar Sedang Terisi
                            </button>
                        @endif
                    @else
                        <a href="{{ route('login') }}" style="text-decoration: none; display: block; text-align: center; background: #1e293b; color: white; padding: 18px; border-radius: 16px; font-weight: 700; font-size: 16px;">
                            Login untuk Menyewa
                        </a>
                    @endauth

                    <p style="text-align: center; font-size: 12px; color: #94a3b8; margin-top: 20px;">
                        🛡️ Pembayaran aman melalui Midtrans
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection