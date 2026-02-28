<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\ListAppointmentRequest;
use App\Http\Resources\Doctor\AppointmentResource;
use App\Services\Doctor\AppointmentService;
use Illuminate\Http\Request;
use App\Models\Appointment;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $service
    ) {}

    /**
     * 1️⃣ Tous les rendez-vous (avec filtre optionnel status)
     * GET /api/doctor/appointments
     */
    public function index(ListAppointmentRequest $request)
    {
        $filters = $request->validated();
        $appointments = $this->service->list($filters);

        if ($appointments->isEmpty()) {
            // Message personnalisé selon le status
            $status = $filters['status'] ?? null;
            $message = match($status) {
                'valide' => 'Vous n’avez aucun rendez-vous validé pour le moment.',
                'annule' => 'Vous n’avez aucun rendez-vous annulé.',
                'en_attente' => 'Vous n’avez aucun rendez-vous en attente.',
                null => 'Vous n’avez aucun rendez-vous pour le moment.',
                default => "Aucun rendez-vous trouvé pour le status '{$status}'."
            };

            return response()->json([
                'message' => $message,
                'data' => []
            ], 200);
        }

        return AppointmentResource::collection($appointments);
    }

     /**
     * 2️⃣ Valider ou annuler un rendez-vous
     * PUT /api/doctor/appointments/{id}/status
     */
   public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:valide,annule'
        ]);

        $appointment = Appointment::find($id);

        if (!$appointment) {
            return response()->json([
                'message' => 'Rendez-vous introuvable.'
            ], 404);
        }

        // On empêche de modifier si le rendez-vous est déjà annulé
        if ($appointment->status === 'annule') {
            $who = $appointment->cancelled_by === 'patient' ? 'le patient' : 'le médecin';
            return response()->json([
                'message' => "Impossible de modifier ce rendez-vous : il a déjà été annulé "
            ], 403);
        }

        // On empêche de valider un rendez-vous annulé (sécurité supplémentaire)
        if ($request->status === 'valide' && $appointment->status === 'annule') {
            return response()->json([
                'message' => 'Impossible de valider un rendez-vous annulé.'
            ], 403);
        }

        // On met à jour le statut
        $appointment->status = $request->status;

        // Si le médecin annule, on note que c’est lui
        if ($request->status === 'annule') {
            $appointment->cancelled_by = 'doctor';
        }

        $appointment->save();

        // Message personnalisé selon le status choisi
        $message = match($request->status) {
            'valide' => 'Rendez-vous validé avec succès.',
            'annule' => 'Rendez-vous annulé avec succès.'
        };

        return response()->json([
            'message' => $message,
            'data' => new AppointmentResource($appointment)
        ], 200);
    }
}