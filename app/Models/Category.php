<?php

namespace App\Models;
use Illuminate\Support\Str; 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    /**
     * Relasi: Satu Kategori punya banyak Kamar
     */
   public function rooms()
{
    return $this->hasMany(Room::class);
}
protected static function boot()
{
    parent::boot();
    static::creating(function ($category) {
        $category->slug = Str::slug($category->name);
    });
}
}