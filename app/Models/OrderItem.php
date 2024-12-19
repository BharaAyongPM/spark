<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    // Menandakan bahwa model ini menggunakan timestamps, jika perlu untuk melacak kapan item pesanan dibuat dan diperbarui
    public $timestamps = true;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'order_id',
        'field_id',
        'slot_start',
        'slot_end',
        'price'
    ];

    // Relasi ke model Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Relasi ke model Field
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
