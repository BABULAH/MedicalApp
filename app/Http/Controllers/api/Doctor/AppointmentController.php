<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        return response()->json(
            Appointment::where('doctor_id', auth()->id())
                ->with('user')
                ->get()
        );
    }

    public function accept(Appointment $appointment)
    {
        $appointment->update(['status' => 'accepte']);
        return response()->json(['message' => 'Rendez-vous accepté']);
    }

    public function reject(Appointment $appointment)
    {
        $appointment->update(['status' => 'refuse']);
        $appointment->timeSlot->update(['is_booked' => false]);

        return response()->json(['message' => 'Rendez-vous refusé']);
    }
}
