<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    // Menandakan bahwa model ini menggunakan timestamps, jika perlu untuk melacak kapan notifikasi dibuat
    public $timestamps = true;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'user_id',
        'message',
        'status'
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
