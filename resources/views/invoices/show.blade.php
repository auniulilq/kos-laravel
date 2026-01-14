@extends('layouts.app')

@section('content')
<div class="container mx-auto py-6">
    <h1 class="text-2xl font-semibold mb-4">Detail Invoice {{ $invoice->period_month }}</h1>

    <div class="mb-4">
        <div><strong>Penyewa:</strong> {{ $invoice->user->name ?? '—' }}</div>
        <div><strong>Jatuh Tempo:</strong> {{ optional($invoice->due_date)->format('d M Y') ?? '-' }}</div>
        <div><strong>Status:</strong> {{ ucfirst($invoice->status) }}</div>
        <div><strong>Total:</strong> Rp {{ number_format($invoice->total_amount, 0, ',', '.') }}</div>
        <div class="mt-2">
            <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-primary">Unduh PDF</a>
        </div>
    </div>

    <div class="bg-white shadow rounded">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Deskripsi</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Jenis</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Bulan</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Jumlah</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse ($invoice->payments as $payment)
                <tr>
                    <td class="px-4 py-2">{{ $payment->notes ?? 'Layanan' }}</td>
                    <td class="px-4 py-2">{{ ucfirst($payment->type) }}</td>
                    <td class="px-4 py-2">{{ $payment->month_year }}</td>
                    <td class="px-4 py-2 text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">Belum ada item di invoice ini.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection