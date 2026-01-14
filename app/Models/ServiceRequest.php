<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Events\ServiceRequestStatusUpdated;

class ServiceRequest extends Model
{
    protected $fillable = [
        'user_id',
        'room_id',
        'service_type',
        'service_option_id',
        'quantity',
        'description',
        'price',
        'status',
        'payment_status',
        'payment_id',
        'admin_notes',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
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

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function serviceOption()
    {
        return $this->belongsTo(ServiceOption::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function getServiceTypeNameAttribute()
    {
        return match($this->service_type) {
            'laundry' => 'Laundry',
            'blanket' => 'Pinjam Selimut',
            'repair' => 'Perbaikan',
            'other' => 'Lainnya',
        };
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => '<span class="badge bg-warning">Menunggu</span>',
            'approved' => '<span class="badge bg-info">Disetujui</span>',
            'in_progress' => '<span class="badge bg-primary">Proses</span>',
            'completed' => '<span class="badge bg-success">Selesai</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
        };
    }

    /**
     * Boot method untuk model event
     */
    protected static function boot()
    {
        parent::boot();

        // Dispatch event saat status berubah
        static::updated(function ($serviceRequest) {
            if ($serviceRequest->isDirty('status')) {
                $oldStatus = $serviceRequest->getOriginal('status');
                event(new ServiceRequestStatusUpdated($serviceRequest, $oldStatus));
            }
        });
    }
}
