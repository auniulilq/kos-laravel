?>

protected function schedule(Schedule $schedule)
{
    // Generate monthly payments on 1st day of month at 00:00
    $schedule->command('payments:generate-monthly')
             ->monthlyOn(1, '00:00')
             ->timezone('Asia/Jakarta');
    
    // Send payment reminders on 5th and 20th of month
    $schedule->command('payments:send-reminders')
             ->monthlyOn(5, '09:00')
             ->timezone('Asia/Jakarta');
             
    $schedule->command('payments:send-reminders')
             ->monthlyOn(20, '09:00')
             ->timezone('Asia/Jakarta');

    $schedule->call(function () {
        $wa = app(\App\Services\WhatsAppService::class);
        $payments = \App\Models\Payment::pending()
            ->thisMonth()
            ->with('user','room')
            ->get();

        foreach ($payments as $payment) {
            $user = $payment->user;
            if (!empty($user->phone) && ($user->whatsapp_opt_in ?? false)) {
                $wa->sendPaymentReminder($user, $payment);
            }
        }
    })->dailyAt('09:00')->timezone('Asia/Jakarta');
}

