<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        return response()->json(
            Availability::where('doctor_id', auth()->id())
                ->with('timeSlots')
                ->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'day_of_week' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        return response()->json(
            Availability::create([
                'doctor_id' => auth()->id(),
                ...$data
            ]),
            201
        );
    }

    public function destroy(Availability $availability)
    {
        $availability->delete();
        return response()->json(['message' => 'Disponibilité supprimée']);
    }
}
