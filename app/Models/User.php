<?php

// app/Models/User.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'provider',
        'provider_id',
        'avatar_url',
        'is_active',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }
    public function sourceInfo()
    {
        return $this->hasOne(UserSource::class);
    }
    public function preferences()
    {
        return $this->hasMany(UserPreference::class);
    }

    public function carts()
    {
        return $this->hasMany(Chart::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
    public function getSaldoSiapTarikAttribute()
    {
        // Hitung total pendapatan
        $totalPendapatan = \App\Models\Order::whereHas('orderItems.field', function ($q) {
            $q->where('user_id', $this->id);
        })->where('status', 'completed')->sum('total_price'); // sesuaikan kolom

        // Hitung withdraw
        $totalWithdrawn = \App\Models\Withdraw::where('user_id', $this->id)
            ->whereIn('status', ['approved', 'completed'])
            ->sum('amount');

        return $totalPendapatan - $totalWithdrawn;
    }
}
