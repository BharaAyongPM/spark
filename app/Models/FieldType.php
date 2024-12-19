<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldType extends Model
{
    // Menandakan bahwa model ini menggunakan timestamps, jika tidak perlu, setel menjadi false
    public $timestamps = false;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'type_name',
        'description'
    ];

    // Relasi ke model Field
    public function fields()
    {
        return $this->hasMany(Field::class, 'field_type_id');
    }
}
