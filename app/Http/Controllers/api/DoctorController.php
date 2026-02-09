<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    // Recherche + filtres
    public function index(Request $request)
    {
        $query = Doctor::with(['speciality', 'establishment', 'locality']);

        if ($request->speciality) {
            $query->where('speciality_id', $request->speciality);
        }

        if ($request->locality) {
            $query->where('locality_id', $request->locality);
        }

        if ($request->name) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', "%{$request->name}%")
                  ->orWhere('last_name', 'like', "%{$request->name}%");
            });
        }

        if ($request->establishment) {
            $query->where('establishment_id', $request->establishment);
        }

        return response()->json(
            $query->where('status', 'actif')->paginate(10)
        );
    }

    // Fiche détaillée médecin
    public function show(Doctor $doctor)
    {
        return response()->json(
            $doctor->load([
                'speciality',
                'establishment',
                'locality',
                'availabilities.timeSlots',
                'reviews.user'
            ])
        );
    }
}
