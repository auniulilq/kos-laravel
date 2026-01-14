<x-app-layout>
    
    <div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
        <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
            
            {{-- Header Salam --}}
            <div style="margin-bottom: 35px;">
                <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
                    Selamat Datang, Admin 👋
                </h1>
                <p style="color: #64748b; margin-top: 5px; font-size: 16px;">Berikut adalah ringkasan operasional kos Anda hari ini.</p>
            </div>

            {{-- Statistik Utama (Quick Stats) --}}
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 40px;">
                
                {{-- Card 1: Pendapatan --}}
                <div style="background: white; padding: 25px; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <div style="width: 45px; height: 45px; background: #ecfdf5; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #10b981; font-size: 20px;">💰</div>
                        <span style="font-size: 14px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Total Pendapatan</span>
                    </div>
                    <div style="font-size: 24px; font-weight: 900; color: #1e293b;">Rp 12.450.000</div>
                    <div style="margin-top: 10px; font-size: 13px; color: #10b981; font-weight: 600;">↑ 12% dari bulan lalu</div>
                </div>

                {{-- Card 2: Penyewa --}}
                <div style="background: white; padding: 25px; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <div style="width: 45px; height: 45px; background: #eff6ff; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #3b82f6; font-size: 20px;">👥</div>
                        <span style="font-size: 14px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Penyewa Aktif</span>
                    </div>
                    <div style="font-size: 24px; font-weight: 900; color: #1e293b;">18 / 20</div>
                    <div style="margin-top: 10px; font-size: 13px; color: #64748b;">2 Kamar tersedia</div>
                </div>

                {{-- Card 3: Layanan Pending --}}
                <div style="background: white; padding: 25px; border-radius: 24px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                        <div style="width: 45px; height: 45px; background: #fffbeb; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #f59e0b; font-size: 20px;">🛠️</div>
                        <span style="font-size: 14px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">Layanan Pending</span>
                    </div>
                    <div style="font-size: 24px; font-weight: 900; color: #1e293b;">5 Permintaan</div>
                    <a href="#" style="display: inline-block; margin-top: 10px; font-size: 13px; color: #3b82f6; text-decoration: none; font-weight: 600;">Cek Detail →</a>
                </div>

            </div>

            {{-- Main Content Section --}}
            <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden;">
                <div style="padding: 30px; border-bottom: 1px solid #f1f5f9;">
                    <h2 style="font-size: 18px; font-weight: 800; color: #1e293b; margin: 0;">Aktivitas Terbaru</h2>
                </div>
                <div style="padding: 40px; text-align: center;">
                    <div style="width: 64px; height: 64px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto; font-size: 24px;">✨</div>
                    <h3 style="font-size: 16px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">Semua Terkendali!</h3>
                    <p style="color: #94a3b8; font-size: 14px; max-width: 400px; margin: 0 auto;">Belum ada notifikasi mendesak. Data statistik dan grafik performa akan muncul di sini secara otomatis.</p>
                </div>
            </div>

            {{-- Quick Actions Footer --}}
            <div style="margin-top: 40px; display: flex; gap: 15px; flex-wrap: wrap;">
                <button style="background: #1e293b; color: white; border: none; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.2s;">+ Tambah Kamar</button>
                <button style="background: white; color: #1e293b; border: 1px solid #e2e8f0; padding: 12px 20px; border-radius: 12px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.2s;">Download Laporan Bulanan</button>
            </div>

        </div>
    </div>
</x-app-layout>