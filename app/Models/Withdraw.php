<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdraw extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'rekening_id',
        'amount',
        'status',
        'admin_id',
        'note',
    ];

    // Relasi ke vendor (user)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke rekening
    public function rekening()
    {
        return $this->belongsTo(Rekening::class);
    }

    // Relasi ke admin yang memproses
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
