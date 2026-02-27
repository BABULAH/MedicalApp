<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'user_id',
        'establishment_id',
        'speciality_id',
        'registration_number',
        'bio',
        'experience_years',
        'phone',
        'email',
        'address',
        'locality_id',
        'latitude',
        'longitude',
        'consultation_price',
        'emergency_price',
        'is_verified',
        'status',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'consultation_price' => 'decimal:2',
        'emergency_price' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    // Toujours charger user
    protected $with = ['user'];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFullNameAttribute(): string
    {
        return $this->user
            ? trim($this->user->first_name . ' ' . $this->user->last_name)
            : '';
    }

    /*
    |--------------------------------------------------------------------------
    | Relations
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    public function availabilities()
    {
        return $this->hasMany(Availability::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function timeSlots()
    {
        return $this->hasManyThrough(TimeSlot::class, Availability::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes utiles (multi-tenant)
    |--------------------------------------------------------------------------
    */

    public function scopeOfEstablishment($query, $establishmentId)
    {
        return $query->where('establishment_id', $establishmentId);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
