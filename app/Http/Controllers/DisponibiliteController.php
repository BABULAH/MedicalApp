<?php

namespace App\Http\Controllers;

use App\Services\AvailabilityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Availability;
use Illuminate\Validation\ValidationException;

class DisponibiliteController extends Controller
{
    //

    public function store(Request $request)
    {
        $data = $request->validate([
            'day_of_week' => 'required|string',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        AvailabilityService::checkOverlap(
            Auth::id(),
            $data['day_of_week'],
            $data['start_time'],
            $data['end_time']
        );

        $availability = Availability::create([
            'doctor_id'  => Auth::id(),
            'day_of_week'=> $data['day_of_week'],
            'start_time' => $data['start_time'],
            'end_time'   => $data['end_time'],
            'is_active'  => true,
        ]);

        return response()->json($availability, 201);
    }

}
