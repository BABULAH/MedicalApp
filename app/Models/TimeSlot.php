<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimeSlot extends Model
{
    protected $fillable = [
        'availability_id',
        'establishment_id',
        'start_time',
        'end_time',
        'is_booked'
    ];

    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id')
                    ->where('role', 'doctor'); // facultatif
    }



    protected static function booted()
    {
        static::creating(function ($timeSlot) {
            if (! $timeSlot->establishment_id && $timeSlot->availability) {
                $timeSlot->establishment_id =
                    $timeSlot->availability->doctor->establishment_id;
            }
        });
    }
    
}
