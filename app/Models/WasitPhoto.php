<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WasitPhoto extends Model
{
    protected $table = 'wasit_photo';

    protected $fillable = [
        'nama',
        'jenis',
        'field_id',
        'keterangan',
        'harga',
    ];

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
