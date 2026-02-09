<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'appointment_id',
        'establishment_id',
        'title',
        'message',
        'type',
        'is_read'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }
}
