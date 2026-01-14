<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $payment->invoice_number }}</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #334155; 
            margin: 0; 
            padding: 40px;
            line-height: 1.5;
        }
        .header-table { width: 100%; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; margin-bottom: 30px; }
        .brand { font-size: 24px; font-weight: 800; color: #1e293b; letter-spacing: -1px; }
        .status-badge { 
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            background: #dcfce7;
            color: #15803d;
        }
        .invoice-info { text-align: right; }
        .invoice-info div { font-size: 13px; color: #64748b; }
        .invoice-info .id { font-size: 18px; font-weight: bold; color: #1e293b; margin-top: 5px; }

        .details-table { width: 100%; margin-top: 30px; border-radius: 10px; overflow: hidden; }
        .details-table th { 
            background: #f8fafc; 
            padding: 12px 15px; 
            text-align: left; 
            font-size: 11px; 
            color: #64748b; 
            text-transform: uppercase;
            border-bottom: 1px solid #e2e8f0;
        }
        .details-table td { 
            padding: 15px; 
            border-bottom: 1px solid #f1f5f9; 
            font-size: 14px; 
        }
        
        .total-section { margin-top: 40px; text-align: right; }
        .total-box { 
            display: inline-block; 
            background: #f8fafc; 
            padding: 20px; 
            border-radius: 12px; 
            min-width: 250px;
        }
        .total-label { font-size: 12px; color: #64748b; font-weight: bold; text-transform: uppercase; }
        .total-amount { font-size: 28px; font-weight: 800; color: #3b82f6; margin-top: 5px; }

        .verification-info { 
            margin-top: 40px; 
            background: #f1f5f9; 
            padding: 15px; 
            border-radius: 8px; 
            font-size: 12px; 
            color: #475569;
        }
        .footer { 
            position: absolute; 
            bottom: 40px; 
            left: 40px; 
            right: 40px; 
            border-top: 1px solid #e2e8f0; 
            padding-top: 20px; 
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <table class="header-table">
        <tr>
            <td>
                <div class="brand">KOS MANAGEMENT</div>
                <div style="font-size: 12px; color: #94a3b8; margin-top: 5px;">Bukti Pembayaran Digital Resmi</div>
            </td>
            <td class="invoice-info">
                <div>Nomor Invoice:</div>
                <div class="id">#{{ $payment->invoice_number }}</div>
                <div style="margin-top: 8px;">
                    <span class="status-badge">LUNAS / VERIFIED</span>
                </div>
            </td>
        </tr>
    </table>

    {{-- Info Utama --}}
    <table style="width: 100%;">
        <tr>
            <td width="50%" style="vertical-align: top;">
                <div style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px;">Penyewa:</div>
                <div style="font-size: 16px; font-weight: bold; color: #1e293b;">{{ $payment->user->name }}</div>
                <div style="font-size: 13px; color: #64748b;">{{ $payment->user->email }}</div>
            </td>
            <td width="50%" style="vertical-align: top; text-align: right;">
                <div style="font-size: 11px; font-weight: bold; color: #94a3b8; text-transform: uppercase; margin-bottom: 8px;">Detail Unit:</div>
                <div style="font-size: 16px; font-weight: bold; color: #1e293b;">Kamar {{ $payment->room->room_number }}</div>
                <div style="font-size: 13px; color: #64748b;">{{ strtoupper($payment->room->type) }} Room</div>
            </td>
        </tr>
    </table>

    {{-- Detail Pembayaran --}}
    <table class="details-table">
        <thead>
            <tr>
                <th>Deskripsi Tagihan</th>
                <th>Periode</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Sewa Kamar Bulanan</td>
                <td>{{ \Carbon\Carbon::parse($payment->month_year)->format('F Y') }}</td>
                <td style="text-align: right; font-weight: bold;">{{ $payment->formatted_amount }}</td>
            </tr>
        </tbody>
    </table>

    {{-- Total --}}
    <div class="total-section">
        <div class="total-box">
            <div class="total-label">Total Dibayarkan</div>
            <div class="total-amount">{{ $payment->formatted_amount }}</div>
        </div>
    </div>

    {{-- Audit Trail --}}
    <div class="verification-info">
        <table style="width: 100%;">
            <tr>
                <td><strong>Dibayar pada:</strong> {{ $payment->paid_at ? $payment->paid_at->format('d M Y, H:i') : '-' }}</td>
                <td style="text-align: right;"><strong>Diverifikasi oleh:</strong> {{ $payment->verifier ? $payment->verifier->name : 'Sistem' }}</td>
            </tr>
            <tr>
                <td colspan="2" style="padding-top: 5px;"><strong>Waktu Verifikasi:</strong> {{ $payment->verified_at ? $payment->verified_at->format('d M Y, H:i') : '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Invoice ini adalah bukti pembayaran yang sah dan diterbitkan secara elektronik.</p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} | &copy; {{ date('Y') }} Kos Management System</p>
    </div>
</body>
</html>