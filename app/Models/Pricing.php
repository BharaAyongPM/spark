<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pricing extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'pricing';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'field_id',
        'price',
        'time_slot',
        'day_type',
    ];

    /**
     * Relasi ke model Field.
     * Setiap harga terkait dengan satu lapangan.
     */
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
