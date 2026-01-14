<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil filter tanggal dari request, default awal bulan s/d hari ini
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $monthlyPayments = Payment::where('status', 'verified')
            ->whereBetween('verified_at', [
                Carbon::parse($startDate)->startOfDay(), 
                Carbon::parse($endDate)->endOfDay()
            ])
            ->with(['user', 'room'])
            ->get();

        $monthlyIncome = $monthlyPayments->sum('amount');

        return view('admin.reports.index', compact(
            'monthlyIncome', 
            'monthlyPayments', 
            'startDate', 
            'endDate'
        ));
    }

    public function monthly(Request $request)
    {
        // Gunakan input 'month' (format Y-m) dari HTML5 <input type="month">
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        
        $payments = Payment::where('status', 'verified')
            ->whereYear('verified_at', Carbon::parse($month)->year)
            ->whereMonth('verified_at', Carbon::parse($month)->month)
            ->with(['user', 'room'])
            ->get();

        $totalIncome = $payments->sum('amount');

        return view('admin.reports.monthly', compact('payments', 'totalIncome', 'month'));
    }

    public function export(Request $request)
    {
        // Logika Export Mendukung Dua Mode: Rentang Tanggal ATAU Bulanan
        if ($request->has('month')) {
            $month = $request->get('month');
            $payments = Payment::where('status', 'verified')
                ->whereYear('verified_at', Carbon::parse($month)->year)
                ->whereMonth('verified_at', Carbon::parse($month)->month)
                ->with(['user', 'room'])
                ->get();
            $label = "Bulan " . Carbon::parse($month)->translatedFormat('F Y');
        } else {
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $payments = Payment::where('status', 'verified')
                ->whereBetween('verified_at', [
                    Carbon::parse($startDate)->startOfDay(), 
                    Carbon::parse($endDate)->endOfDay()
                ])
                ->with(['user', 'room'])
                ->get();
            $label = "$startDate s.d $endDate";
            // Set $month dummy agar Blade PDF tidak error
            $month = $startDate; 
        }

        $totalIncome = $payments->sum('amount');

        // Gunakan variabel yang sama dengan yang diminta di pdf.blade.php
        $pdf = Pdf::loadView('admin.reports.pdf', compact('payments', 'totalIncome', 'month'));
        
        return $pdf->download("Laporan_Pemasukan_{$label}.pdf");
    }

public function downloadPdf(Request $request)
{
    // Ambil bulan dari request, default ke bulan sekarang jika kosong
    $month = $request->get('month', date('Y-m'));
    
    // Ambil data laporan berdasarkan bulan
    $reports = Payment::where('payment_date', 'like', $month . '%')
                      ->where('status', 'success')
                      ->get();

    $pdf = Pdf::loadView('admin.reports.pdf', [
        'reports' => $reports,
        'month'   => $month, // <--- PASTIKAN VARIABEL INI DIKIRIM
    ]);

    return $pdf->download('laporan-bulanan.pdf');
}
}