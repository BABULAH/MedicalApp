<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Establishment extends Model
{
    protected $fillable = [
        'name',
        'type', // hospital, clinic, cabinet
        'address',
        'locality_id',
        'latitude',
        'longitude',
        'phone',
        'email'
    ];

    // Relations
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function admins()
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }

    public function appointmentReasons()
    {
        return $this->hasMany(AppointmentReason::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function locality()
    {
        return $this->belongsTo(Locality::class);
    }

    public function specialities()
    {
        return $this->hasMany(Speciality::class);
    }

}
