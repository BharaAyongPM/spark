<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    // Relasi ke model Field
    public function fields()
    {
        return $this->hasMany(Field::class);
    }
}
