@extends('layouts.app')

@section('title', 'Detail Kamar ' . $room->room_number)

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Header Navigation --}}
        <div style="margin-bottom: 30px; display: flex; align-items: center; justify-content: space-between;">
            <a href="{{ route('user.rooms.index') }}" style="text-decoration: none; color: #64748b; font-weight: 600; font-size: 14px; display: flex; align-items: center; gap: 8px; transition: 0.2s;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Daftar Kamar
            </a>
            <div style="font-size: 14px; color: #94a3b8;">
                Home / Kamar / <span style="color: #1e293b; font-weight: 600;">{{ $room->room_number }}</span>
            </div>
        </div>

        {{-- Alert Errors --}}
        @if($errors->any() || session('error'))
            <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 16px; border-radius: 16px; margin-bottom: 24px; font-size: 14px;">
                @if(session('error')) {{ session('error') }} @endif
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </div>
        @endif

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
                                Kategori: {{ strtoupper($room->category->name ?? 'Standard') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Info & Facilities --}}
                <div style="background: white; border-radius: 24px; padding: 40px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.04);">
                    <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 24px;">Kamar #{{ $room->room_number }}</h1>
                    
                    <p style="color: #64748b; line-height: 1.7; margin-bottom: 32px; font-size: 16px;">
                        {{ $room->description ?? 'Kamar yang nyaman dengan pencahayaan yang baik, cocok untuk pelajar atau pekerja profesional yang menginginkan ketenangan di lingkungan yang strategis.' }}
                    </p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                        <div>
                            <h3 style="font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">Spesifikasi Kamar</h3>
                            <ul style="list-style: none; padding: 0; display: grid; gap: 16px;">
                                <li style="display: flex; align-items: center; gap: 12px; color: #334155; font-weight: 500;">
                                    <span style="background: #f1f5f9; padding: 10px; border-radius: 12px; font-size: 18px;">📐</span> 
                                    Ukuran {{ $room->area ?? '3 x 4' }} Meter
                                </li>
                                <li style="display: flex; align-items: center; gap: 12px; color: #334155; font-weight: 500;">
                                    <span style="background: #f1f5f9; padding: 10px; border-radius: 12px; font-size: 18px;">⚡</span> 
                                    Listrik {{ $room->electricity ?? 'Termasuk' }}
                                </li>
                                <li style="display: flex; align-items: center; gap: 12px; color: #334155; font-weight: 500;">
                                    <span style="background: #f1f5f9; padding: 10px; border-radius: 12px; font-size: 18px;">👥</span> 
                                    Maks. {{ $room->capacity ?? '2' }} Orang
                                </li>
                            </ul>
                        </div>
                        
                       {{-- Ganti bagian Fasilitas Tersedia dengan ini --}}
<div>
    <h3 style="font-size: 14px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">Fasilitas Tersedia</h3>
    <div style="display: flex; flex-wrap: wrap; gap: 8px;">
        @php 
            $rawFacilities = $room->facilities;
            $roomFacilityIds = [];

            // 1. Konversi data ke Array ID
            if (is_array($rawFacilities)) {
                $roomFacilityIds = $rawFacilities;
            } elseif (is_string($rawFacilities)) {
                $decoded = json_decode($rawFacilities, true);
                // Jika JSON valid pakai hasil decode, jika tidak (string koma) pakai explode
                $roomFacilityIds = is_array($decoded) ? $decoded : explode(',', $rawFacilities);
            }

            // 2. Bersihkan spasi dan pastikan hanya ID yang diproses
            $roomFacilityIds = array_map('trim', array_filter((array)$roomFacilityIds));
            
            // 3. Ambil data dari database
            $activeFacilities = \App\Models\Facility::whereIn('id', $roomFacilityIds)->get();
        @endphp
        
        @forelse($activeFacilities as $fac)
            <span style="background: #eff6ff; color: #2563eb; padding: 6px 14px; border-radius: 30px; font-size: 13px; font-weight: 600; border: 1px solid #dbeafe; display: flex; align-items: center; gap: 6px;">
                {{ $fac->icon ?? '✨' }} {{ $fac->name }}
            </span>
        @empty
            {{-- Fallback jika tidak ada ID yang cocok di tabel Facility, 
                 tampilkan teks mentahnya saja agar tidak kosong sama sekali --}}
            @if(count($roomFacilityIds) > 0 && !is_numeric($roomFacilityIds[0]))
                @foreach($roomFacilityIds as $textFac)
                    <span style="background: #f1f5f9; color: #475569; padding: 6px 14px; border-radius: 30px; font-size: 13px; font-weight: 600; border: 1px solid #e2e8f0;">
                        {{ $textFac }}
                    </span>
                @endforeach
            @else
                <span style="color: #94a3b8; font-style: italic;">Fasilitas standar tersedia</span>
            @endif
        @endforelse
    </div>
</div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: Booking Card --}}
            <div class="right-column" x-data="{ 
                durasi: 'Bulanan', 
                selectedDate: '{{ now()->toDateString() }}',
                basePrice: {{ $room->price }},
                method: 'full',
                get prices() {
                    return {
                        'Mingguan': Math.round(this.basePrice / 3.5),
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
                get displayPrice() {
                    return this.method === 'dp' ? this.basePrice : this.hargaSekarang
                }
            }">
                <div style="background: white; border-radius: 28px; padding: 32px; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05); position: sticky; top: 40px;">
                    
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

                    @if($room->user_id === auth()->id())
                        <div style="background: #eff6ff; border: 1px solid #dbeafe; padding: 20px; border-radius: 16px; text-align: center;">
                            <span style="font-size: 24px;">🏠</span>
                            <h4 style="color: #1e40af; font-weight: 800; margin-top: 10px;">Kamar Anda</h4>
                            <p style="color: #60a5fa; font-size: 13px; margin-bottom: 0;">Anda tercatat sebagai penghuni aktif kamar ini.</p>
                        </div>
                    @elseif($room->status === 'empty')
                        <form action="{{ route('bookings.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="room_id" value="{{ $room->id }}">
                            
                            <div style="display: grid; gap: 20px; border-top: 1px solid #f1f5f9; padding-top: 24px; margin-bottom: 24px;">
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 8px;">Tanggal Mulai Kos</label>
                                    <input type="date" name="start_date" x-model="selectedDate" required style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; font-family: 'Inter';">
                                </div>
                                
                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 8px;">Durasi Sewa</label>
                                    <select name="duration" x-model="durasi" style="width: 100%; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; background: white; cursor: pointer;">
                                        <option value="Mingguan">Per Minggu</option>
                                        <option value="Bulanan">Per Bulan</option>
                                        <option value="Tahunan">Per Tahun</option>
                                    </select>
                                </div>

                                <div>
                                    <label style="display: block; font-size: 12px; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 12px;">Metode Pembayaran</label>
                                    <div style="display: grid; gap: 10px;">
                                        <label style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; cursor: pointer;" :style="method === 'full' ? 'border-color: #2563eb; background: #f8faff' : ''">
                                            <input type="radio" name="payment_method" value="full" x-model="method" checked>
                                            <span style="font-size: 14px; font-weight: 600; color: #1e293b;">Bayar Lunas</span>
                                        </label>
                                        <label x-show="durasi !== 'Mingguan'" style="display: flex; align-items: center; gap: 10px; padding: 12px; border: 1px solid #e2e8f0; border-radius: 12px; cursor: pointer;" :style="method === 'dp' ? 'border-color: #2563eb; background: #f8faff' : ''">
                                            <input type="radio" name="payment_method" value="dp" x-model="method">
                                            <span style="font-size: 14px; font-weight: 600; color: #1e293b;">DP 1 Bulan</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div style="background: #f8fafc; border-radius: 16px; padding: 20px; margin-bottom: 24px; border: 1px solid #f1f5f9;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 14px;">
                                    <span style="color: #64748b;">Masa sewa berakhir:</span>
                                    <span style="font-weight: 700; color: #1e293b;" x-text="checkoutDate"></span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 14px; padding-top: 12px; border-top: 1px dashed #e2e8f0;">
                                    <span style="color: #64748b;" x-text="method === 'dp' ? 'Tagihan Sekarang (DP):' : 'Total Pembayaran:'"></span>
                                    <span style="font-weight: 800; color: #2563eb; font-size: 16px;">Rp<span x-text="displayPrice.toLocaleString('id-ID')"></span></span>
                                </div>
                            </div>

                            <button type="submit" 
                                    style="width: 100%; background: #2563eb; color: white; border: none; padding: 18px; border-radius: 16px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.2s; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);"
                                    onmouseover="this.style.background='#1d4ed8'; this.style.transform='translateY(-2px)'"
                                    onmouseout="this.style.background='#2563eb'; this.style.transform='translateY(0)'">
                                Booking Kamar Sekarang
                            </button>
                        </form>
                    @else
                        <button disabled style="width: 100%; background: #94a3b8; color: white; border: none; padding: 18px; border-radius: 16px; font-weight: 700; cursor: not-allowed;">
                            Kamar Sedang Terisi
                        </button>
                    @endif

                    <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #f1f5f9;">
                        <h5 style="font-size: 13px; font-weight: 800; color: #475569; text-transform: uppercase; margin-bottom: 12px;">Catatan Penting</h5>
                        <ul style="padding: 0; list-style: none; display: grid; gap: 8px; font-size: 13px; color: #64748b;">
                            <li style="display: flex; gap: 8px;"><span>✓</span> Harga sudah termasuk air</li>
                            <li style="display: flex; gap: 8px;"><span>✓</span> Check-in: 14.00, Check-out: 12.00</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection