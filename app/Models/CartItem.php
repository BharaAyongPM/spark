<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    // Menandakan bahwa model menggunakan timestamps, jika tidak, setel menjadi false
    public $timestamps = true;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'cart_id',
        'field_id',
        'slot_start',
        'slot_end',
        'price'
    ];

    // Relasi ke model Cart
    public function cart()
    {
        return $this->belongsTo(Chart::class, 'cart_id');
    }

    // Relasi ke model Field
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
