<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemasukan {{ $month }}</title>
    <style>
        @page { 
            margin: 1.5cm; 
        }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            color: #334155; 
            margin: 0; 
            padding: 0;
            line-height: 1.5;
        }
        
        /* Header & Brand */
        .header-table { width: 100%; border-bottom: 3px solid #1e293b; padding-bottom: 10px; margin-bottom: 25px; }
        .brand { font-size: 24px; font-weight: bold; color: #1e293b; letter-spacing: -0.5px; }
        .report-title { font-size: 12px; text-transform: uppercase; color: #64748b; letter-spacing: 1px; margin-top: 5px; }
        .period-box { text-align: right; }
        .period-label { font-size: 10px; text-transform: uppercase; color: #64748b; }
        .period-value { font-size: 16px; font-weight: bold; color: #1e293b; }

        /* Summary Card */
        .summary-container { 
            margin-bottom: 30px; 
            width: 100%;
        }
        .summary-card { 
            background: #f8fafc; 
            border: 1px solid #e2e8f0;
            padding: 20px; 
            border-radius: 10px;
        }
        .summary-label { font-size: 11px; color: #64748b; text-transform: uppercase; font-weight: bold; margin-bottom: 5px; }
        .summary-value { font-size: 26px; font-weight: bold; color: #10b981; }
        .summary-count { font-size: 18px; font-weight: bold; color: #1e293b; }

        /* Main Table */
        table.main-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.main-table th { 
            background: #f1f5f9; 
            color: #475569; 
            font-size: 10px; 
            font-weight: bold; 
            text-transform: uppercase; 
            padding: 12px 10px;
            border-bottom: 2px solid #e2e8f0;
            text-align: left;
        }
        table.main-table td { 
            border-bottom: 1px solid #f1f5f9; 
            padding: 12px 10px; 
            font-size: 11px; 
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-mono { font-family: 'Courier', monospace; color: #64748b; }
        .row-even { background-color: #fafafa; }

        /* Footer & Signatures */
        .signature-wrapper { margin-top: 50px; width: 100%; }
        .signature-box { width: 200px; text-align: center; float: right; }
        .signature-space { height: 80px; }
        .signature-name { font-weight: bold; border-top: 1px solid #1e293b; padding-top: 5px; font-size: 12px; }

        .footer { 
            clear: both;
            margin-top: 100px; 
            font-size: 9px; 
            color: #94a3b8; 
            text-align: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <table class="header-table">
        <tr>
            <td>
                <div class="brand">KOS MANAGEMENT</div>
                <div class="report-title">Monthly Income Report</div>
            </td>
            <td class="period-box">
                <div class="period-label">Periode Laporan</div>
                <div class="period-value">{{ \Carbon\Carbon::parse($month)->translatedFormat('F Y') }}</div>
            </td>
        </tr>
    </table>

    <div class="summary-container">
        <table width="100%">
            <tr>
                <td width="60%" style="padding-right: 15px;">
                    <div class="summary-card">
                        <div class="summary-label">Total Pemasukan Bersih</div>
                        <div class="summary-value">Rp {{ number_format($totalIncome, 0, ',', '.') }}</div>
                    </div>
                </td>
                <td width="40%">
                    <div class="summary-card">
                        <div class="summary-label">Volume Transaksi</div>
                        <div class="summary-count">{{ $payments->count() }} Item Terverifikasi</div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="18%">No. Invoice</th>
                <th width="27%">Nama Penyewa</th>
                <th width="10%" class="text-center">Kamar</th>
                <th width="20%" class="text-right">Nominal</th>
                <th width="20%" class="text-center">Tgl Verifikasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($payments as $index => $payment)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $payment->invoice_number }}</td>
                <td style="font-weight: bold; color: #1e293b;">{{ $payment->user->name }}</td>
                <td class="text-center">{{ $payment->room->room_number }}</td>
                <td class="text-right" style="font-weight: bold; color: #1e293b;">{{ $payment->formatted_amount }}</td>
                <td class="text-center">{{ $payment->verified_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-wrapper">
        <div class="signature-box">
            <p style="font-size: 11px; margin-bottom: 10px;">Dicetak pada, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="font-size: 11px; font-weight: bold; margin-bottom: 40px;">Manager Operasional,</p>
            <div class="signature-space"></div>
            <div class="signature-name">( ____________________ )</div>
        </div>
    </div>

    <div class="footer">
        <p>Laporan ini dihasilkan secara otomatis oleh Sistem Manajemen Kos pada {{ now()->format('d/m/Y H:i') }}</p>
        <p>Dokumen ini merupakan catatan internal resmi dan tidak memerlukan stempel fisik tambahan.</p>
    </div>
</body>
</html>