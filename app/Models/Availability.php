<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    protected $fillable = [
        'doctor_id',
        'establishment_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];


   public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id'); // pointe directement vers users
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function timeSlots()
    {
        return $this->hasMany(TimeSlot::class);
    }

    // App\Models\Availability.php

    protected static function booted()
    {
        static::creating(function ($availability) {
            if (! $availability->establishment_id && $availability->doctor) {
                $availability->establishment_id =
                    $availability->doctor->establishment_id;
            }
        });

        static::updating(function ($availability) {
            if ($availability->doctor) {
                $availability->establishment_id =
                    $availability->doctor->establishment_id;
            }
        });
    }

}
