<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('Kawan Kost', 'Kawan Kost') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

@vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #3b82f6;
            --primary-hover: #2563eb;
            --bg-gradient: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif !important;
            background: var(--bg-gradient);
            margin: 0;
            padding: 0;
            color: var(--text-main);
        }
        
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        
        .auth-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            padding: 48px;
            max-width: 450px;
            width: 100%;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }
        
        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }

        .auth-logo a {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: -1px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .auth-logo .dot {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
        }
        
        /* Override Laravel default classes if used inside $slot */
        input[type="email"], 
        input[type="password"], 
        input[type="text"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            margin-top: 8px;
            margin-bottom: 20px;
            transition: all 0.2s;
            background: white;
            box-sizing: border-box;
        }
        
        input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        
        label {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        button, .btn-primary {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
            margin-top: 10px;
        }
        
        button:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }
        
        .auth-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 14px;
            color: var(--text-muted);
        }
        
        .auth-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .auth-link:hover {
            text-decoration: underline;
        }

        .error-message {
            background: #fef2f2;
            color: #991b1b;
            padding: 12px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            border: 1px solid #fee2e2;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div style="display: flex; flex-direction: column; align-items: center; width: 100%; max-width: 450px;">
            
            <div class="auth-card">
                <div class="auth-logo">
                    <a href="/">
                        <div class="dot"></div>
                        {{ strtoupper(config('Kawan Kost', 'Kawan Kost')) }}
                    </a>
                </div>
                
                <div class="auth-content">
                    {{ $slot }}
                </div>
            </div>

            <footer style="margin-top: 40px; text-align: center; width: 100%;">
                
                <p style="color:rgb(187, 208, 237); font-size: 12px; margin-bottom: 16px;">
                    © {{ date('Y') }} Kawan Kost. All rights reserved.
                </p>
                
                <div style="display: flex; justify-content: center; gap: 20px;">
                    <a href="#" style="color: #64748b; text-decoration: none; font-size: 12px; font-weight: 600;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">Syarat & Ketentuan</a>
                    <a href="#" style="color: #64748b; text-decoration: none; font-size: 12px; font-weight: 600;" onmouseover="this.style.color='#1e293b'" onmouseout="this.style.color='#64748b'">Kebijakan Privasi</a>
                </div>
            </footer>
        </div>
    </div>
</body>
</html>