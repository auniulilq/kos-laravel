@extends('layouts.app')

@section('title', 'Dashboard Utama')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 1000px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Header Section --}}
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
                    Halo, {{ Auth::user()->name }}! 👋
                </h1>
                <p style="color: #64748b; margin-top: 5px; font-size: 16px;">Selamat datang kembali di hunian nyaman Anda.</p>
            </div>
            <div style="text-align: right;">
                <span style="display: block; font-size: 12px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">Kamar Anda</span>
                <span style="font-size: 20px; font-weight: 900; color: #3b82f6;">{{ Auth::user()->room->room_number ?? 'Belum Ada Kamar' }}</span>
            </div>
        </div>

        @if (session('status'))
            <div style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 15px; border-radius: 12px; margin-bottom: 25px; font-size: 14px; font-weight: 600;">
                {{ session('status') }}
            </div>
        @endif

        {{-- Quick Action Cards --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px;">
            
            {{-- Tagihan Card --}}
            <div style="background: white; border-radius: 24px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 50px; height: 50px; background: #fff1f2; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px;">💳</div>
                <h3 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 10px;">Pembayaran</h3>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 20px; line-height: 1.5;">Cek tagihan bulanan dan riwayat pembayaran Anda di sini.</p>
                <a href="{{ route('user.payments.index') }}" style="text-decoration: none; display: inline-block; font-weight: 700; color: #3b82f6; font-size: 14px;">Lihat Tagihan →</a>
            </div>

            {{-- Layanan Card --}}
            <div style="background: white; border-radius: 24px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 50px; height: 50px; background: #eff6ff; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px;">🛎️</div>
                <h3 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 10px;">Layanan</h3>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 20px; line-height: 1.5;">Butuh laundry, cuci selimut, atau perbaikan AC? Ajukan sekarang.</p>
                <a href="{{ route('user.services.index') }}" style="text-decoration: none; display: inline-block; font-weight: 700; color: #3b82f6; font-size: 14px;">Ajukan Layanan →</a>
            </div>

            {{-- Notifikasi Card --}}
            <div style="background: white; border-radius: 24px; padding: 30px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); transition: 0.3s;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                <div style="width: 50px; height: 50px; background: #fefce8; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 20px;">🔔</div>
                <h3 style="font-size: 18px; font-weight: 800; color: #1e293b; margin-bottom: 10px;">Pemberitahuan</h3>
                <p style="color: #64748b; font-size: 14px; margin-bottom: 20px; line-height: 1.5;">Jangan lewatkan info penting dari pengelola kos mengenai hunian Anda.</p>
                <a href="{{ route('user.notifications') }}" style="text-decoration: none; display: inline-block; font-weight: 700; color: #3b82f6; font-size: 14px;">Buka Inbox →</a>
            </div>

        </div>

        {{-- Support / Footer Section --}}
        <div style="background: #1e293b; border-radius: 24px; padding: 30px; color: white; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="font-size: 32px;">🎧</div>
                <div>
                    <h4 style="margin: 0; font-size: 16px; font-weight: 700;">Butuh Bantuan Mendesak?</h4>
                    <p style="margin: 5px 0 0 0; font-size: 14px; opacity: 0.7;">Hubungi admin kami via WhatsApp untuk respon cepat.</p>
                </div>
            </div>
            <a href="https://wa.me/628123456789" target="_blank" style="text-decoration: none; background: #25d366; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 700; font-size: 14px; transition: 0.2s;" onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                WhatsApp Admin
            </a>
        </div>

    </div>
</div>
@endsection