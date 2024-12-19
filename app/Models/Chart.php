<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    // Menandakan bahwa model mungkin atau tidak menggunakan timestamps
    // Jika tabel charts Anda menggunakan timestamps, Anda dapat menghapus baris ini atau set true
    public $timestamps = false;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'user_id' // asumsi hanya menyimpan user_id, tambahkan kolom lain sesuai kebutuhan
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
