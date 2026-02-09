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
        'status'
    ];

      // Toujours charger la relation user
    protected $with = ['user'];
    // Accesseurs
    public function getFullNameAttribute(): string
    {
        return "{$this->user->first_name} {$this->user->last_name}";
    }

  

    // Relations
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

    public function appointmentReasons()
    {
        return $this->hasMany(AppointmentReason::class);
    }
    public function timeSlots()
    {
        return $this->hasManyThrough(TimeSlot::class, Availability::class);
    }

    
}