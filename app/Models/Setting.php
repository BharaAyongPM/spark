<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';

    protected $fillable = [
        'fee_service',
        'fee_xendit',
        'fee_penarikan',
        'baner1',
        'baner2',
        'baner3',
        'deskripsi',
        'nama_web',
        'email_suport',
        'wa_suport',
    ];
}
