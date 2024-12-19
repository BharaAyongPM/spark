<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldFacility extends Model
{
    // Tabel pivot tidak memerlukan timestamps kecuali Anda memang ingin melacaknya
    public $timestamps = false;

    // Spesifikasikan nama tabel jika berbeda dari konvensi naming Laravel
    protected $table = 'field_facilities';

    // Tentukan kolom yang bisa diisi
    protected $fillable = [
        'field_id',
        'facility_id'
    ];

    // Jika Anda ingin mengatur relasi dari model pivot kembali ke model utama
    public function field()
    {
        return $this->belongsTo(Field::class, 'field_id');
    }

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
}
