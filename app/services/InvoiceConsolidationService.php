<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class InvoiceConsolidationService
{
    public function getPeriodMonthForPayment(Payment $payment): string
    {
        return $payment->month_year ?? Carbon::parse($payment->created_at)->format('Y-m');
    }

    public function getOrCreateDraftInvoice(int $userId, string $periodMonth, ?string $dueDate = null): Invoice
    {
        return Invoice::firstOrCreate(
            ['user_id' => $userId, 'period_month' => $periodMonth],
            [
                'status' => 'draft',
                'due_date' => $dueDate,
                'total_amount' => 0,
            ]
        );
    }

    public function attachPaymentToInvoice(Payment $payment): Invoice
    {
        return DB::transaction(function () use ($payment) {
            $period = $this->getPeriodMonthForPayment($payment);
            $defaultDue = Carbon::parse($period.'-01')->addDays(10)->format('Y-m-d');
            $invoice = $this->getOrCreateDraftInvoice($payment->user_id, $period, $defaultDue);

            $payment->invoice()->associate($invoice);
            $payment->save();

            $this->recalcTotal($invoice);
            return $invoice;
        });
    }

    public function recalcTotal(Invoice $invoice): void
    {
        $total = Payment::where('invoice_id', $invoice->id)->sum('amount');
        $invoice->total_amount = $total;
        $invoice->save();
    }

    public function issueInvoice(Invoice $invoice): void
    {
        $invoice->status = 'issued';
        $invoice->save();
    }

    public function markPaid(Invoice $invoice): void
    {
        $invoice->status = 'paid';
        $invoice->save();
    }
}