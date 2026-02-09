<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Availability;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Services\AvailabilityService;


class AvailabilityController extends Controller
{


    /**
     * Affiche toutes les disponibilités du médecin connecté
     */
    public function index()
    {
        $availabilities = Availability::where('doctor_id', Auth::id())
            ->with('timeSlots')
            ->get();

        return response()->json($availabilities);
    }

    /**
     * Crée une nouvelle disponibilité en vérifiant les chevauchements
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'day_of_week' => 'required|string',
            'start_time'  => 'required|date_format:H:i',
            'end_time'    => 'required|date_format:H:i|after:start_time',
        ]);

        // Vérifier le chevauchement avec d'autres disponibilités du même médecin et jour
        $overlap = Availability::where('doctor_id', Auth::id())
            ->where('day_of_week', $data['day_of_week'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                      ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                      ->orWhere(function ($q) use ($data) {
                          // Cas où la nouvelle dispo englobe une dispo existante
                          $q->where('start_time', '<=', $data['start_time'])
                            ->where('end_time', '>=', $data['end_time']);
                      });
            })
            ->exists();

        if ($overlap) {
            throw ValidationException::withMessages([
                'start_time' => ['Ce créneau horaire chevauche une disponibilité existante.'],
            ]);
        }

        $availability = Availability::create([
            'doctor_id' => Auth::id(),
            'day_of_week' => $data['day_of_week'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'is_active' => 1,
        ]);

        return response()->json($availability, 201);
    }

    /**
     * Supprime une disponibilité
     */
    public function destroy(Availability $availability)
    {
        // Vérifier que le médecin connecté est le propriétaire
        if ($availability->doctor_id !== Auth::id()) {
            return response()->json(['message' => 'Non autorisé'], 403);
        }

        $availability->delete();

        return response()->json(['message' => 'Disponibilité supprimée']);
    }
}
