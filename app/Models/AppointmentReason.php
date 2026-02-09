<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentReason extends Model
{
    protected $fillable = [
        'name',
        'description',
        'establishment_id'
    ];

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
