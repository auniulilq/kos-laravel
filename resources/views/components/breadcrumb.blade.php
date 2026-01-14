@props(['breadcrumbs' => []])

<nav aria-label="Breadcrumb" style="margin: 20px 0 28px 0;">
    <ol style="display: flex; list-style: none; padding: 0; margin: 0; align-items: center; font-family: 'Inter', sans-serif;">
        <li style="display: flex; align-items: center;">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('user.dashboard') }}" 
               style="color: #94a3b8; text-decoration: none; display: flex; align-items: center; transition: all 0.2s;"
               onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#94a3b8'">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </a>
        </li>

        @foreach($breadcrumbs as $breadcrumb)
            <li style="display: flex; align-items: center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="margin: 0 10px;">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>

                @if(!$loop->last)
                    <a href="{{ $breadcrumb['url'] }}" 
                       style="color: #64748b; text-decoration: none; font-size: 13px; font-weight: 500; transition: all 0.2s;"
                       onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#64748b'">
                        {{ $breadcrumb['title'] }}
                    </a>
                @else
                    <span style="color: #1e293b; font-size: 13px; font-weight: 700; letter-spacing: -0.2px;">
                        {{ $breadcrumb['title'] }}
                    </span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>