<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    // Aktifkan jika Anda ingin menggunakan timestamp (created_at, updated_at)
    public $timestamps = false;

    // Definisikan fillable properties untuk memberi tahu Laravel kolom mana yang dapat diisi
    protected $fillable = [
        'user_id',
        'activity_type',
        'activity_description',
        'activity_date'
    ];

    // Kolom 'activity_date' akan dihandle sebagai timestamp
    protected $dates = ['activity_date'];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
