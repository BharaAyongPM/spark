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
        'automatic',
        'scope'
    ];

    // Relasi ke model Field (jika field_id tidak null dan scope specific)
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
