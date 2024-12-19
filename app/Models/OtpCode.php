<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpCode extends Model
{

    // Menandakan bahwa model ini tidak menggunakan timestamps secara default
    public $timestamps = false;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'user_id',
        'otp_code',
        'expiry_time',
        'used'
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Menyimpan expiry_time sebagai instance Carbon untuk manipulasi tanggal yang lebih mudah
    protected $dates = ['expiry_time'];
}
