<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\{CancelAppointmentRequest, StoreAppointmentRequest};
use App\Http\Resources\Patient\AppointmentResource;
use App\Services\Patient\AppointmentService;
use App\Models\Appointment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;


class PatientAppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $service
    ) {}


    public function index(Request $request)
    {
        $patient = auth()->user();

        // Récupère tous les rendez-vous du patient
        $appointments = $patient->appointments()
            ->orderBy('date', 'desc')
            ->get();

        // Retourne en JSON avec la resource
        return response()->json([
            'message' => 'Liste des rendez-vous du patient.',
            'data' => AppointmentResource::collection($appointments)
        ]);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->service->create($request->validated());

        return response()->json([
            'message' => 'Rendez-vous demandé avec succès.',
            'data' => new AppointmentResource($appointment)
        ], 201);
    }


 /**
     * Annuler un rendez-vous par le patient
     * PATCH /api/patient/appointments/{id}/cancel
     */
    public function cancel(Request $request, $id)
    {
        // Cherche le rendez-vous du patient connecté
        $appointment = Appointment::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$appointment) {
            return response()->json([
                'message' => 'Rendez-vous introuvable.'
            ], 404);
        }

        // Empêche d’annuler si déjà annulé
        if ($appointment->status === 'annule') {
            $who = $appointment->cancelled_by === 'patient' ? 'vous' : 'le médecin';
            return response()->json([
                'message' => "Impossible d’annuler ce rendez-vous : il a déjà été annulé par {$who}."
            ], 403);
        }

        // Met à jour le statut et note que c’est le patient qui annule
        $appointment->status = 'annule';
        $appointment->cancelled_by = 'patient';
        $appointment->save();

        return response()->json([
            'message' => 'Rendez-vous annulé avec succès.',
            'data' => new AppointmentResource($appointment)
        ], 200);
    }
}