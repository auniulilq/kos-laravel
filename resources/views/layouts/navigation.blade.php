<nav style="position: sticky; top: 0; z-index: 50; background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 12px 0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
    <div style="max-width: 1280px; margin: 0 auto; padding: 0 20px;">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 20px;">
            
            <div style="display: flex; align-items: center; gap: 30px; flex: 1;">
                <a href="{{ Auth::check() ? (Auth::user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard')) : url('/') }}" 
                   style="font-size: 20px; font-weight: 800; color: #1e293b; text-decoration: none; font-family: 'Inter', sans-serif; letter-spacing: -0.5px;">
                    <span style="color: #3b82f6;">Kawan </span>Kost

                <div class="nav-container">
                    @auth
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
                            <a href="{{ route('admin.rooms.index') }}" class="nav-link {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">Kamar</a>
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">Penyewa</a>
                            <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">Pembayaran</a>
                            <a href="{{ route('admin.services.index') }}" class="nav-link {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">Pengajuan</a>
                            <a href="{{ route('admin.service-options.index') }}" class="nav-link {{ request()->routeIs('admin.service-options.*') ? 'active' : '' }}">Layanan</a>
                            <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">Laporan</a>
                        @else
                            <a href="{{ route('user.dashboard') }}" class="nav-link {{ request()->routeIs('user.dashboard') ? 'active' : '' }}">Dashboard</a>
                            <a href="{{ route('user.payments.index') }}" class="nav-link {{ request()->routeIs('user.payments.*') ? 'active' : '' }}">Pembayaran</a>
                            <a href="{{ route('user.services.index') }}" class="nav-link {{ request()->routeIs('user.services.*') ? 'active' : '' }}">Layanan</a>
<a href="{{ route('user.notifications') }}" 
   class="nav-link {{ request()->routeIs('user.notifications.*') ? 'active' : '' }}" 
   style="display: flex; align-items: center; gap: 6px; position: relative;">
    
    Notifikasi

    @if(isset($unread_notifications) && $unread_notifications > 0)
        {{-- Bulatan Merah --}}
        <span style="
            background: #ef4444; 
            color: white; 
            font-size: 10px; 
            font-weight: 800; 
            padding: 2px 6px; 
            border-radius: 10px; 
            min-width: 18px; 
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
            animation: pulse-notif 2s infinite;
        ">
            {{ $unread_notifications > 9 ? '9+' : $unread_notifications }}
        </span>
    @endif
</a>                        @endif
                    @else
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                    @endauth
                </div>
            </div>

            @auth
            <div style="display: flex; align-items: center; gap: 12px;">
                <div class="profile-pill">
                    <div style="width: 28px; height: 28px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: bold;">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <a href="{{ route('profile.edit') }}" style="color: #475569; text-decoration: none; font-size: 13px; font-weight: 600;">
                        {{ auth()->user()->name }}
                    </a>
                    <span class="role-tag {{ auth()->user()->isAdmin() ? 'admin' : 'user' }}">
                        {{ auth()->user()->isAdmin() ? 'Admin' : 'User' }}
                    </span>
                </div>

                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="logout-btn-modern" title="Logout">
                        <svg style="width: 18px; height: 18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
            @endauth

        </div>
    </div>
</nav>

<style>
    .nav-container { display: flex; gap: 5px; align-items: center; }
    
    .nav-link {
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 12px;
        border-radius: 8px;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        transition: all 0.2s;
    }

    .nav-link:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .nav-link.active {
        background: #eff6ff;
        color: #3b82f6;
        font-weight: 600;
    }

    .profile-pill {
        background: #f8fafc;
        padding: 4px 12px 4px 6px;
        border-radius: 999px;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .role-tag {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .role-tag.admin { background: #fee2e2; color: #ef4444; }
    .role-tag.user { background: #dcfce7; color: #22c55e; }

    .logout-btn-modern {
        background: transparent;
        color: #94a3b8;
        border: none;
        padding: 8px;
        cursor: pointer;
        border-radius: 8px;
        display: flex;
        align-items: center;
        transition: all 0.2s;
    }

    .logout-btn-modern:hover {
        background: #fee2e2;
        color: #ef4444;
    }

    @keyframes pulse-notif {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); box-shadow: 0 0 8px rgba(239, 68, 68, 0.6); }
    100% { transform: scale(1); }
}

    @media (max-width: 768px) {
        .nav-container { display: none; } /* Perlu menu hamburger untuk mobile */
        .profile-pill span { display: none; }
    }
</style>