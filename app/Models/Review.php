<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    // Menandakan bahwa model ini menggunakan timestamps, jika ingin melacak kapan ulasan dibuat
    public $timestamps = true;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'field_id',
        'user_id',
        'rating',
        'comment'
    ];

    // Relasi ke model Field
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
