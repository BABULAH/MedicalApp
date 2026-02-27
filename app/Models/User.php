<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $guard_name = 'api';

    public function getJWTIdentifier()
    {
        return $this->getKey(); // retourne l'id
    }

    public function getJWTCustomClaims(): array
    {
        return []; // tu peux ajouter des infos personnalisées ici
    }


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
        'establishment_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden Fields
    |--------------------------------------------------------------------------
    */

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth'     => 'date',
        'latitude'          => 'decimal:8',
        'longitude'         => 'decimal:8',
    ];

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    */

    public function setPasswordAttribute($value): void
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Boot (Multi-tenant auto assign)
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function ($user) {
            if (auth()->check() && auth()->user()->hasRole('admin')) {
                $user->establishment_id = auth()->user()->establishment_id;
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeOfEstablishment($query, int $establishmentId)
    {
        return $query->where('establishment_id', $establishmentId);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getNameAttribute(): string
    {
        return $this->full_name;
    }

    

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
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
}
