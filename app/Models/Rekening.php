<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'rekening';

    // Kolom yang bisa diisi
    protected $fillable = [
        'id_user',
        'nama_bank',
        'rekening',
        'nama',
        'email'
    ];

    /**
     * Relasi dengan model User.
     * Setiap rekening dimiliki oleh satu user.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
    public function withdraws()
    {
        return $this->hasMany(Withdraw::class);
    }
}
