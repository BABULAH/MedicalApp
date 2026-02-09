<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;

class AvailabilityController extends Controller
{
    public function byDoctor(Doctor $doctor)
    {
        return response()->json(
            $doctor->availabilities()
                   ->with('timeSlots')
                   ->where('is_active', true)
                   ->get()
        );
    }
}
