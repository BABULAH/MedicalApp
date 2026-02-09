<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use HasRoles;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'gender',
        'date_of_birth',
        'address',
        'locality_id',
        'latitude',
        'longitude',
        'role',
        'establishment_id' // lien tenant
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

  protected static function booted()
    {
        static::updating(function ($user) {
            if (
                auth()->check() &&
                auth()->id() === $user->id &&
                $user->isDirty('role')
            ) {
                // On empêche toute modification du rôle
                $user->role = $user->getOriginal('role');
            }
        });

        static::creating(function ($user) {
            if (auth()->user()?->role === 'admin') {
                $user->establishment_id = auth()->user()->establishment_id;
            }
        });
    }



    // User.php
    public function scopeOfEstablishment($query, $establishmentId)
    {
        return $query->where('establishment_id', $establishmentId);
    }

    // Accessor pour nom complet

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getNameAttribute(): string
    {
        return trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? ''));
    }


    // Relations
    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

}
