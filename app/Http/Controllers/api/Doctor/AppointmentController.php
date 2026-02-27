<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\ListAppointmentRequest;
use App\Http\Resources\Doctor\AppointmentResource;
use App\Services\Doctor\AppointmentService;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $service
    ) {}

public function index(ListAppointmentRequest $request)
{
    $filters = $request->validated();

    $appointments = $this->service->list($filters);

    if ($appointments->isEmpty()) {
        // Message dynamique selon le filtre status
        $status = $filters['status'] ?? null;

        $message = match($status) {
            'attente' => 'Vous n\'avez aucun rendez-vous en attente.',
            'valide' => 'Vous n\'avez aucun rendez-vous validé.',
            'annule' => 'Vous n\'avez aucun rendez-vous annulé.',
            default => 'Vous n\'avez aucun rendez-vous.',
        };

        return response()->json([
            'message' => $message,
            'data' => []
        ], 200);
    }

    return AppointmentResource::collection($appointments);
}

    public function indexWatcher()
    {
        // Récupérer uniquement les rendez-vous avec status 'attente'
        $appointments = $this->service->listAttente([
            'status' => 'attente'
        ]);

        if ($appointments->isEmpty()) {
            return response()->json([
                'message' => 'Vous n\'avez aucun rendez-vous en attente.',
                'data' => []
            ], 200);
        }

        return AppointmentResource::collection($appointments);
    }
}