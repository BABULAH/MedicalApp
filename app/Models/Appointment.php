<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id',
        'doctor_id',
        'date',
        'availability_id',
        'time_slot_id',
        'appointment_reason_id',
        'status',
        'doctor_comment',
        'cancelled_by',
        'establishment_id',
    ];

    // Define the possible statutes
    const STATUS_PENDING  = 'attente';
    const STATUS_APPROVED = 'valide';
    const STATUS_CANCELLED = 'annule';

    public static function getStatuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_APPROVED,
            self::STATUS_CANCELLED,
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function establishment() { return $this->belongsTo(Establishment::class); }
    public function timeSlot() { return $this->belongsTo(TimeSlot::class); }
    public function reason() { return $this->belongsTo(AppointmentReason::class, 'appointment_reason_id'); }
    public function availability() { return $this->belongsTo(Availability::class, 'availability_id'); }
}

