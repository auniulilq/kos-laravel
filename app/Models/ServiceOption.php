<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceOption extends Model
{
    protected $fillable = [
        'service_type',
        'name',
        'pricing_type',
        'unit_name',
        'price',
        'min_qty',
        'max_qty',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('service_type', $type);
    }

    // Helpers
    public function getFormattedPriceAttribute(): ?string
    {
        if ($this->pricing_type === 'quote' || $this->price === null) {
            return null;
        }
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}