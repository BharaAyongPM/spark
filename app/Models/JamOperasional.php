<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamOperasional extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'jam_operasional';

    // Kolom yang bisa diisi secara massal
    protected $fillable = [
        'field_id',
        'senin',
        'selasa',
        'rabu',
        'kamis',
        'jumat',
        'sabtu',
        'minggu',
    ];

    /**
     * Relasi ke model Field.
     * Setiap jam operasional terkait dengan satu lapangan.
     */
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }
}
