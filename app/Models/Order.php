<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // Menandakan bahwa model ini menggunakan timestamps, jika perlu untuk melacak kapan pesanan dibuat
    public $timestamps = true;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'user_id',
        'total_price',
        'status',
        'discount_code',
        'payment_status',
        'invoice_number',
        'addon_price',
        'due_date'

    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke model OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Relasi ke model Payment jika perlu
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Relasi ke model Discount jika ada diskon yang terkait
    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_code', 'code');
    }
    public function field()
    {
        return $this->belongsTo(Field::class);
    }

    // Field.php
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id'); // Misalkan setiap field memiliki 'owner_id'
    }
}
