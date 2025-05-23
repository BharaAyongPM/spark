<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    // Menandakan bahwa model ini menggunakan timestamps, jika tidak perlu, setel menjadi false
    public $timestamps = true;

    // Array fillable menunjukkan kolom apa yang dapat diisi melalui mass assignment
    protected $fillable = [
        'user_id',
        'field_type_id',
        'name',
        'slug',
        'deskripsi',
        'foto',
        'baner',
        'location',
        'location_id',
        'lat',
        'lon',
        'gallery',
        'slot_tipe',
        'owner',
        'grass_quality',
        'no_whatsapp',
        'custom_domain',
        'instagram',
        'facebook',
        'video',
        'batas_pembayaran',
        'syarat_ketentuan',
        'status',
    ];

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke model FieldType
    public function fieldType()
    {
        return $this->belongsTo(FieldType::class, 'field_type_id');
    }

    // Relasi many-to-many ke model Facility melalui tabel pivot field_facilities
    public function facilities()
    {
        return $this->belongsToMany(Facility::class, 'field_facilities');
    }

    // Relasi one-to-many untuk model Discount jika perlu
    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    // Relasi one-to-many untuk reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Relasi one-to-many untuk orders through order_items
    public function orders()
    {
        return $this->hasManyThrough(Order::class, OrderItem::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function jamOperasionals()
    {
        return $this->hasMany(JamOperasional::class, 'field_id');
    }
}
