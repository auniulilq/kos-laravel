<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function indexUser(Request $request)
    {
        $invoices = Invoice::where('user_id', Auth::id())
            ->orderByDesc('period_month')
            ->paginate(6);

        return view('user.invoices.index', compact('invoices'));
    }

    public function indexAdmin(Request $request)
    {
        $invoices = Invoice::with('user')
            ->orderByDesc('period_month')
            ->paginate(6);

        return view('admin.invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $this->authorizeView($invoice);
        $invoice->load(['user', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    public function downloadPdf(Invoice $invoice)
    {
        $this->authorizeView($invoice);
        $invoice->load(['user', 'payments']);
        $pdf = Pdf::loadView('invoices.pdf', ['invoice' => $invoice]);
        $filename = 'Invoice_'.$invoice->user->id.'_'.$invoice->period_month.'.pdf';
        return $pdf->download($filename);
    }

    private function authorizeView(Invoice $invoice): void
    {
        if (!Auth::user()) {
            abort(403);
        }
        // Sederhana: user hanya boleh melihat invoice miliknya; admin bebas
        if (Auth::user()->role !== 'admin' && $invoice->user_id !== Auth::id()) {
            abort(403);
        }
    }
}