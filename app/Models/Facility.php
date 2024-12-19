<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    // Menandakan bahwa model ini menggunakan timestamps, jika tidak perlu, setel menjadi false
    public $timestamps = false;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'name',
        'description'
    ];

    // Relasi ke model Field melalui tabel pivot field_facilities
    public function fields()
    {
        return $this->belongsToMany(Field::class, 'field_facilities');
    }
}
