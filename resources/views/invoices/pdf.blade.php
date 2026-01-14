<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->period_month }}</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { border: 1px solid #ddd; padding: 6px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Invoice Bulanan</h2>
        <div>Periode: {{ $invoice->period_month }}</div>
        <div>Penyewa: {{ $invoice->user->name ?? '—' }}</div>
        <div>Jatuh Tempo: {{ optional($invoice->due_date)->format('d M Y') ?? '-' }}</div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Deskripsi</th>
                <th>Jenis</th>
                <th>Bulan</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($invoice->payments as $payment)
            <tr>
                <td>{{ $payment->notes ?? 'Layanan' }}</td>
                <td>{{ ucfirst($payment->type) }}</td>
                <td>{{ $payment->month_year }}</td>
                <td class="text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th class="text-right">Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>