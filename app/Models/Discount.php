<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{

    // Menandakan bahwa model menggunakan timestamps, jika tidak, setel menjadi false
    public $timestamps = false;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'code',
        'percentage',
        'start_date',
        'end_date',
        'field_id',
        'user_id',
        'automatic',
        'scope',
        'max_usage',
        'used_count',
        'max_discount',
        'min_order_total',
        'status',
        'description',
    ];
    protected $casts = [
        'automatic' => 'boolean',
        'status' => 'boolean',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    // Relasi ke model Field (jika field_id tidak null dan scope specific)
    // Relasi dengan Field
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    // Relasi dengan User (admin/vendor yang membuat diskon)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope: diskon aktif dan dalam periode berlaku
    public function scopeActive($query)
    {
        return $query->where('status', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }
}
