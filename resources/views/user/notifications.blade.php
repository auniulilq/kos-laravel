@extends('layouts.app')

@section('title', 'Notifikasi Saya')

@section('content')
<div style="background: #f8fafc; min-height: 100vh; font-family: 'Inter', sans-serif; padding: 40px 0;">
    <div style="max-width: 800px; margin: 0 auto; padding: 0 20px;">
        
        {{-- Header Section --}}
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px;">
            <div>
                <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin: 0; letter-spacing: -0.5px;">
                    Notifikasi
                </h1>
                <p style="color: #64748b; margin-top: 5px; font-size: 15px;">Info terbaru mengenai tagihan dan layanan Anda.</p>
            </div>
            <a href="{{ route('user.dashboard') }}" style="text-decoration: none; color: #3b82f6; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                Dashboard →
            </a>
        </div>

        {{-- Notification List --}}
        <div style="background: white; border-radius: 24px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            @forelse($notifications as $notif)
                <div style="display: flex; gap: 20px; padding: 24px; border-bottom: 1px solid #f1f5f9; transition: 0.2s; position: relative; {{ !$notif->is_read ? 'background: #fcfdfe;' : '' }}" 
                     onmouseover="this.style.background='#f8fafc'" 
                     onmouseout="this.style.background='{{ !$notif->is_read ? '#fcfdfe' : 'transparent' }}'">
                    
                    {{-- Status Indicator Dot --}}
                    @if(!$notif->is_read)
                        <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: #3b82f6;"></div>
                    @endif

                    {{-- Icon Container --}}
                    <div style="flex-shrink: 0; width: 48px; height: 48px; border-radius: 12px; background: {{ !$notif->is_read ? '#eff6ff' : '#f1f5f9' }}; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        {{ !$notif->is_read ? '🔔' : '✔️' }}
                    </div>

                    {{-- Content --}}
                    <div style="flex-grow: 1;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                            <strong style="font-size: 16px; color: #1e293b; font-weight: 700;">{{ $notif->title }}</strong>
                            <span style="font-size: 12px; color: #94a3b8; font-weight: 500;">{{ $notif->created_at->diffForHumans() }}</span>
                        </div>
                        <p style="font-size: 14px; color: #64748b; margin: 0 0 12px 0; line-height: 1.5;">{{ $notif->message }}</p>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 12px; color: #cbd5e1;">{{ $notif->created_at->format('d M Y, H:i') }}</span>
                            
                            @if(!$notif->is_read)
                                <form action="{{ route('user.notifications.read', $notif) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" style="background: transparent; border: 1px solid #e2e8f0; color: #475569; padding: 6px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#1e293b'; this.style.color='white'" onmouseout="this.style.background='transparent'; this.style.color='#475569'">
                                        Tandai Dibaca
                                    </button>
                                </form>
                            @else
                                <span style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px;">Sudah Dibaca</span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div style="padding: 80px 40px; text-align: center;">
                    <div style="font-size: 50px; margin-bottom: 20px; opacity: 0.5;">📩</div>
                    <h3 style="font-size: 18px; color: #1e293b; margin-bottom: 8px;">Tidak Ada Notifikasi</h3>
                    <p style="color: #94a3b8; font-weight: 500;">Inbox Anda bersih. Semua update akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($notifications->hasPages())
        <div style="margin-top: 25px;">
            {{ $notifications->links() }}
        </div>
        @endif

    </div>
</div>
@endsection