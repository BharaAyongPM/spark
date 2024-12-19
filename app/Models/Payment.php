<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // Menandakan bahwa model ini menggunakan timestamps, jika perlu untuk melacak kapan pembayaran dibuat
    public $timestamps = true;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'order_id',
        'payment_method',
        'amount',
        'payment_status',
        'payment_date'
    ];

    // Relasi ke model Order
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Menyimpan payment_date sebagai instance Carbon untuk manipulasi tanggal yang lebih mudah
    protected $dates = ['payment_date'];
}
