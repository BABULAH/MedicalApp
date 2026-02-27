<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\ListTimeSlotRequest;
use App\Http\Resources\Patient\TimeSlotResource;
use App\Services\Patient\TimeSlotService;

class TimeSlotController extends Controller
{
    protected $service;

    public function __construct(TimeSlotService $service)
    {
        $this->service = $service;
    }

    public function index(ListTimeSlotRequest $request)
    {
        // Récupérer les paramètres depuis la query ou la route
        $filters = [
            'doctor_id' => $request->doctor_id ?? $request->route('doctor_id'),
            'availability_id' => $request->availability_id ?? $request->route('availability_id'),
        ];

        // Appel du service pour récupérer les créneaux
        $timeSlots = $this->service->list($filters);

        // Vérifier si la collection est vide et personnaliser le message
        if ($timeSlots->isEmpty()) {
            if (!empty($filters['doctor_id'])) {
                $message = "Aucun créneau horaire trouvé pour ce médecin.";
            } elseif (!empty($filters['availability_id'])) {
                $message = "Aucun créneau horaire trouvé pour cette disponibilité.";
            } else {
                $message = "Aucun créneau horaire trouvé.";
            }

            return response()->json(['message' => $message], 200);
        }

        // Retourner la collection JSON
        return TimeSlotResource::collection($timeSlots);
    }


        /*-->    * Lister les créneaux d'une disponibilité précise
     * Lister les créneaux d'une disponibilité précise
     */
    public function listByAvailability(int $availabilityId)
    {
        $timeSlots = $this->service->listByAvailability($availabilityId);

        if ($timeSlots->isEmpty()) {
            return response()->json([
                'message' => 'Aucun créneau horaire trouvé pour cette disponibilité.'
            ], 200);
        }

        return TimeSlotResource::collection($timeSlots);
    }
}