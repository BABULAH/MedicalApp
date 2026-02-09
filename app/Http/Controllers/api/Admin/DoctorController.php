<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Liste des médecins (recherche avancée)
     *
     * Filtres possibles :
     * - speciality_id
     * - name
     * - locality_id
     * - establishment_id
     * - latitude & longitude (géolocalisation)
     *
     * @group Médecins
     */
    public function index(Request $request)
    {
        $query = Doctor::with([
            'speciality',
            'establishment',
            'locality'
        ])->where('status', 'active');

        // 🔍 Filtre spécialité
        if ($request->filled('speciality_id')) {
            $query->where('speciality_id', $request->speciality_id);
        }

        // 🔍 Filtre nom
        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->name . '%')
                  ->orWhere('last_name', 'like', '%' . $request->name . '%');
            });
        }

        // 🔍 Filtre localité
        if ($request->filled('locality_id')) {
            $query->where('locality_id', $request->locality_id);
        }

        // 🔍 Filtre établissement
        if ($request->filled('establishment_id')) {
            $query->where('establishment_id', $request->establishment_id);
        }

        // ⭐ Géolocalisation (latitude & longitude)
        if ($request->filled(['latitude', 'longitude'])) {
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            $query->selectRaw(
                "doctors.*, 
                (6371 * acos(
                    cos(radians(?)) 
                    * cos(radians(latitude)) 
                    * cos(radians(longitude) - radians(?)) 
                    + sin(radians(?)) 
                    * sin(radians(latitude))
                )) AS distance",
                [$latitude, $longitude, $latitude]
            )
            ->orderBy('distance');
        }

        return response()->json(
            $query->paginate(10)
        );
    }

    /**
     * Détails d’un médecin
     *
     * @group Médecins
     */
    public function show(Doctor $doctor)
    {
        return response()->json(
            $doctor->load([
                'speciality',
                'establishment',
                'locality',
                'availabilities',
                'reviews'
            ])
        );
    }
}
