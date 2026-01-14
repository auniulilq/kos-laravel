<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kawan Kost | Hunian Modern & Terintegrasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass-nav { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
        .hero-gradient { background: radial-gradient(circle at top right, #eff6ff 0%, #ffffff 100%); }
    </style>
</head>

<body class="text-slate-900 antialiased">
    <nav class="glass-nav sticky top-0 z-50 border-b border-slate-200 px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center gap-2">
                                <span class="text-xl font-extrabold tracking-tight text-slate-900">Kawan Kost</span>
            </div>
            <div class="flex gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="bg-slate-900 text-white px-5 py-2.5 rounded-full text-sm font-bold transition hover:bg-slate-800">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-slate-600 px-5 py-2.5 text-sm font-bold hover:text-blue-600">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-full text-sm font-bold transition hover:bg-blue-700 shadow-lg shadow-blue-200">Daftar Sekarang</a>
                    @endif
                @endauth
            </div>
        </div>
    </nav>

    <main class="hero-gradient">
        <section class="max-w-7xl mx-auto px-6 pt-20 pb-16 text-center">
            <span class="inline-block px-4 py-1.5 bg-blue-50 text-blue-600 rounded-full text-xs font-bold mb-6 uppercase tracking-widest">Premium Living Space</span>
            <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 leading-[1.1] mb-6">
                Temukan Kenyamanan <br><span class="text-blue-600">Hunian Masa Kini.</span>
            </h1>
            <p class="text-slate-500 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
                Kelola hunian, pembayaran tagihan, dan layanan fasilitas dalam satu platform yang cerdas dan transparan.
            </p>
            
            <div class="flex flex-wrap justify-center gap-8 md:gap-16 border-y border-slate-200 py-10">
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-slate-900">{{ $totalRooms }}</div>
                    <div class="text-sm font-medium text-slate-400 uppercase tracking-tighter">Total Unit</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-green-600">{{ $occupiedRooms }}</div>
                    <div class="text-sm font-medium text-slate-400 uppercase tracking-tighter">Terisi</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-extrabold text-blue-600">{{ $vacantRooms }}</div>
                    <div class="text-sm font-medium text-slate-400 uppercase tracking-tighter">Tersedia</div>
                </div>
            </div>
        </section>

        <section class="max-w-5xl mx-auto px-6 -mt-12 relative z-10">
            <div class="bg-white p-4 rounded-[32px] shadow-2xl shadow-slate-200 border border-slate-100">
                <form action="{{ route('rooms.index') }}" method="GET" class="flex flex-wrap items-center gap-4">
                    <div class="flex-1 min-w-[200px] border-r border-slate-100 px-4">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Cari Lokasi/Lantai</label>
                        <input type="text" name="search" placeholder="Contoh: Lantai 2..." class="w-full focus:outline-none text-slate-900 font-semibold placeholder-slate-300">
                    </div>
                    <div class="w-full md:w-40 border-r border-slate-100 px-4">
                        <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Kategori</label>
                        <select name="type" class="w-full focus:outline-none font-semibold text-slate-900 bg-transparent">
    <option value="">Semua</option>
    @foreach($categories as $cat)
    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
    @endforeach
</select>
                    </div>
                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-2xl font-bold transition flex items-center gap-2 ml-auto">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Cari Kamar
                    </button>
                </form>
            </div>
        </section>

        <section class="max-w-7xl mx-auto px-6 py-24">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Kamar Tersedia</h2>
                    <p class="text-slate-500 mt-2">Pilih unit terbaik yang sesuai dengan kebutuhan Anda.</p>
                </div>
                <a href="#" class="text-blue-600 font-bold text-sm hover:underline">Lihat Semua Unit →</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
               @foreach($rooms as $room)
    <div class="group bg-white rounded-[24px] border border-slate-200 overflow-hidden transition-all hover:shadow-xl hover:shadow-slate-200">
        <div class="relative aspect-[4/3] overflow-hidden">
            <img src="{{ $room->image ? asset('storage/' . $room->image) : 'https://picsum.photos/seed/'.$room->id.'/600/450' }}" 
                 class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
            
            <div class="absolute top-4 left-4">
                <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-widest text-slate-900 shadow-sm border border-slate-100">
                    {{ strtoupper($room->category->name ?? 'No Category') }}
                </span>
            </div>
        </div>

       <div class="p-6">
    <div class="flex justify-between items-start mb-2">
        <div>
            <h3 class="text-xl font-extrabold text-slate-900 group-hover:text-blue-600 transition">
                Unit {{ $room->room_number }}
            </h3>
            <p class="text-slate-400 text-sm font-medium">{{ $room->location ?? 'Main Building' }}</p>
        </div>
        <div class="text-right">
            <span class="text-xl font-black text-blue-600">Rp{{ number_format($room->price, 0, ',', '.') }}</span>
            <span class="block text-[10px] font-bold text-slate-400 uppercase">Per Bulan</span>
        </div>
    </div>

    <p class="text-slate-500 text-sm leading-relaxed mb-4 line-clamp-2">
        {{ $room->description ?? 'Kamar nyaman dengan fasilitas lengkap, lokasi strategis dan lingkungan tenang.' }}
    </p>

           <div class="flex flex-wrap gap-2 mb-6">
    @php
        // Pastikan $roomFacilities selalu menjadi array
        $roomFacilities = $room->facilities;
        
        // Jika ternyata dia masih berbentuk string (karena belum ter-cast sempurna), decode manual
        if (is_string($roomFacilities)) {
            $roomFacilities = json_decode($roomFacilities, true) ?? [];
        }
    @endphp

    @forelse($roomFacilities as $facId)
        @php 
            // Ambil data fasilitas dari variabel $facilities yang dikirim controller
            $fac = $facilities->find($facId); 
        @endphp
        
        @if($fac)
            <div class="flex items-center gap-1 text-[10px] font-bold text-slate-500 bg-slate-50 px-2 py-1 rounded-md">
                ✅ {{ $fac->name }}
            </div>
        @endif
    @empty
        <div class="text-[10px] text-slate-400 italic">Fasilitas Standar</div>
    @endforelse
</div>
            <a href="{{ route('rooms.show', $room->id) }}" class="block w-full text-center py-3.5 rounded-xl border-2 border-slate-100 font-bold text-slate-900 transition hover:bg-slate-900 hover:text-white hover:border-slate-900">
                Detail Kamar
            </a>
        </div>
    </div>
@endforeach
            </div>
            
            <div class="mt-16 flex justify-center">
                {{ $rooms->links() }}
            </div>
        </section>

        <section class="bg-slate-500 py-24 px-6 text-white rounded-t-[60px]">
            <div class="max-w-7xl mx-auto text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-extrabold mb-4">Mengapa Memilih Kawan Kost?</h2>
                <p class="text-black-400 max-w-xl mx-auto">Kami mengedepankan kemudahan teknologi untuk kenyamanan hunian Anda.</p>
            </div>
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center">
                    <div class="w-16 h-16 bg-white-600/20 rounded-2xl flex items-center justify-center text-black-500 text-3xl mx-auto mb-6">💳</div>
                    <h4 class="text-xl font-bold mb-3">Auto Billing</h4>
                    <p class="text-black-400 text-sm leading-relaxed">Tagihan otomatis setiap bulan dengan beragam metode pembayaran digital yang aman.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-white-600/20 rounded-2xl flex items-center justify-center text-black-500 text-3xl mx-auto mb-6">🛠️</div>
                    <h4 class="text-xl font-bold mb-3">One-Click Maintenance</h4>
                    <p class="text-black-400 text-sm leading-relaxed">Laporkan kerusakan kamar atau minta layanan laundry langsung dari dashboard Anda.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-white-600/20 rounded-2xl flex items-center justify-center text-black-500 text-3xl mx-auto mb-6">📱</div>
                    <h4 class="text-xl font-bold mb-3">Notifikasi Real-time</h4>
                    <p class="text-black-400 text-sm leading-relaxed">Dapatkan info pemeliharaan atau pengumuman kos langsung ke WhatsApp Anda.</p>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-slate-900 border-t border-slate-800 py-12 px-6">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-gray-600 rounded-lg flex items-center justify-center text-white font-black">i</div>
                <span class="text-lg font-extrabold text-white tracking-tight">Kawan Kost</span>
            </div>
            <p class="text-slate-500 text-sm">© {{ date('Y') }} Kawan Kost. All rights reserved.</p>
            <div class="flex gap-6 text-slate-400 text-sm font-medium">
                <a href="#" class="hover:text-white">Syarat & Ketentuan</a>
                <a href="#" class="hover:text-white">Kebijakan Privasi</a>
            </div>
        </div>
    </footer>
</body>
</html>