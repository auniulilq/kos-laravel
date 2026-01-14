<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Kawan Kost') }} - @yield('title', 'Dashboard')</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: #2563eb;
            --bg-body: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        /* Layout Fix: Memastikan footer tetap di bawah */
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            line-height: 1.6;
        }

        /* main.container-fluid {
            flex: 1;
            width: 100%;
            max-width: 1280px;
            margin: 0 auto;
            padding: 40px 20px;
            box-sizing: border-box;
        } */

        /* Toast notifications */
        #toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            pointer-events: none;
        }

        .toast {
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 300px;
            max-width: 450px;
            background: white;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            border-radius: 12px;
            padding: 14px 18px;
            pointer-events: auto;
            opacity: 0;
            transform: translateX(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .toast-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            flex-shrink: 0;
        }

        .toast-success .toast-icon { background: #10b981; }
        .toast-error .toast-icon { background: #ef4444; }
        .toast-warning .toast-icon { background: #f59e0b; }
        .toast-info .toast-icon { background: #3b82f6; }

        .toast-content { display: flex; flex-direction: column; }
        .toast-title { font-weight: 700; font-size: 14px; color: #1e293b; }
        .toast-message { font-size: 13px; color: #64748b; }

        .toast-close {
            margin-left: auto;
            border: none;
            background: transparent;
            color: #94a3b8;
            cursor: pointer;
            font-size: 18px;
            padding: 4px;
        }
    </style>
</head>
<body>

    @include('layouts.navigation')

    <main class="container-fluid">
        {{ $slot ?? '' }}
        @yield('content')
    </main>

    {{-- Footer yang lebih manis --}}
    <footer style="background: #0f172a; border-top: 1px solid #1e293b; padding: 40px 0; margin-top: auto;">
                   <p style="color: #64748b; font-size: 13px; margin: 0; text-align: center;">
                &copy; {{ date('Y') }} Kawan Kost. Made with ❤️ for better living.
            </p>
        </div>
    </footer>

    <div id="toast-container"></div>

    <script>
        function showToast(type, message, title) {
            const container = document.getElementById('toast-container');
            const el = document.createElement('div');
            el.className = `toast toast-${type}`;
            
            const titles = { success: 'Berhasil', error: 'Gagal', warning: 'Peringatan', info: 'Informasi' };
            const icons = { success: '✓', error: '✕', warning: '!', info: 'i' };

            el.innerHTML = `
                <div class="toast-icon">${icons[type]}</div>
                <div class="toast-content">
                    <div class="toast-title">${title || titles[type]}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close">&times;</button>
            `;

            container.appendChild(el);
            setTimeout(() => el.classList.add('show'), 10);

            const remove = () => {
                el.classList.remove('show');
                setTimeout(() => el.remove(), 300);
            };

            el.querySelector('.toast-close').onclick = remove;
            setTimeout(remove, 5000);
        }

        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success')) showToast('success', @json(session('success'))); @endif
            @if(session('error')) showToast('error', @json(session('error'))); @endif
            @if(session('warning')) showToast('warning', @json(session('warning'))); @endif
            @if(session('info')) showToast('info', @json(session('info'))); @endif
            @if($errors->any()) showToast('error', @json($errors->first())); @endif
        });
    </script>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
</body>
</html>