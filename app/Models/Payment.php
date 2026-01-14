<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Invoice;
use App\Events\PaymentStatusUpdated;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'invoice_number',
        'user_id',
        'room_id',
        'amount',
        'month_year',
        'status',
        'proof_image',
        'paid_at',
        'verified_at',
        'verified_by',
        'notes',
        'snap_token',
        
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function serviceRequest()
    {
        return $this->hasOne(ServiceRequest::class);
    }

    
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeThisMonth($query)
    {
        return $query->where('month_year', Carbon::now()->format('Y-m'));
    }

    // Static methods
    public static function generateInvoiceNumber()
    {
        $lastInvoice = static::latest('id')->first();
        $number = $lastInvoice ? intval(substr($lastInvoice->invoice_number, 4)) + 1 : 1;
        return 'INV-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
    public function scopeInMonth($query, $monthYear)
{
    // Jika monthYear formatnya '2023-10'
    $date = Carbon::parse($monthYear);
    return $query->whereMonth('verified_at', $date->month)
                 ->whereYear('verified_at', $date->year);
}


public function getFormattedAmountAttribute()
{
    return 'Rp ' . number_format($this->amount, 0, ',', '.');
}

public function getStatusBadgeAttribute()
{
    $statusClasses = [
        'pending'  => 'background: #fef3c7; color: #d97706;', // Kuning
        'success'  => 'background: #dcfce7; color: #16a34a;', // Hijau (Midtrans)
        'paid'     => 'background: #dcfce7; color: #16a34a;', // Hijau (Manual)
        'verified' => 'background: #dbeafe; color: #2563eb;', // Biru
        'failed'   => 'background: #fee2e2; color: #dc2626;', // Merah
        'rejected' => 'background: #fee2e2; color: #dc2626;', // Merah
    ];

    $style = $statusClasses[$this->status] ?? 'background: #f1f5f9; color: #475569;';
    $label = ucfirst($this->status === 'success' ? 'Lunas' : $this->status);

    return "<span style='padding: 4px 10px; border-radius: 8px; font-weight: 700; font-size: 12px; $style'>$label</span>";
}

    /**
     * Boot method untuk model event
     */
    protected static function boot()
    {
        parent::boot();

        // Dispatch event saat status berubah
        static::updated(function ($payment) {
            if ($payment->isDirty('status')) {
                $oldStatus = $payment->getOriginal('status');
                event(new PaymentStatusUpdated($payment, $oldStatus));
            }
        });
    }
}