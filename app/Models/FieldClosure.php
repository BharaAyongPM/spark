<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldClosure extends Model
{
    // Menandakan bahwa model ini menggunakan timestamps, jika tidak perlu, setel menjadi false
    public $timestamps = false;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'field_id',
        'closure_start',
        'closure_end',
        'reason'
    ];

    // Relasi ke model Field
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
