<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\TimeSlot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Liste des rendez-vous du patient connecté
     *
     * @authenticated
     * @group Rendez-vous (Patient)
     */
    public function index()
    {
        $appointments = Appointment::with([
                'doctor',
                'timeSlot',
                'reason'
            ])
            ->where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->get();

        return response()->json($appointments);
    }

    /**
     * Détails d’un rendez-vous
     *
     * @authenticated
     * @group Rendez-vous (Patient)
     */
    public function show(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        return response()->json(
            $appointment->load(['doctor', 'timeSlot', 'reason'])
        );
    }

    /**
     * Créer un rendez-vous
     *
     * @authenticated
     * @group Rendez-vous (Patient)
     *
     * @bodyParam doctor_id int required ID du médecin
     * @bodyParam date date required Date du rendez-vous
     * @bodyParam time_slot_id int required ID du créneau
     * @bodyParam appointment_reason_id int required Motif
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'time_slot_id' => 'required|exists:time_slots,id',
            'appointment_reason_id' => 'required|exists:appointment_reasons,id',
        ]);

        // Vérifier que le créneau n'est pas déjà réservé
        $timeSlot = TimeSlot::findOrFail($data['time_slot_id']);

        if ($timeSlot->is_booked) {
            return response()->json([
                'message' => 'Ce créneau est déjà réservé.'
            ], 422);
        }

        $appointment = Appointment::create([
            'user_id' => Auth::id(),
            'doctor_id' => $data['doctor_id'],
            'date' => $data['date'],
            'time_slot_id' => $data['time_slot_id'],
            'appointment_reason_id' => $data['appointment_reason_id'],
            'status' => 'pending',
        ]);

        // Bloquer le créneau
        $timeSlot->update(['is_booked' => true]);

        return response()->json([
            'message' => 'Demande de rendez-vous envoyée',
            'appointment' => $appointment->load(['doctor', 'timeSlot', 'reason'])
        ], 201);
    }

    /**
     * Annuler un rendez-vous
     *
     * @authenticated
     * @group Rendez-vous (Patient)
     */
    public function cancel(Appointment $appointment)
    {
        $this->authorizeAppointment($appointment);

        if ($appointment->status === 'cancelled') {
            return response()->json([
                'message' => 'Ce rendez-vous est déjà annulé'
            ], 422);
        }

        $appointment->update([
            'status' => 'cancelled',
            'cancelled_by' => 'patient',
        ]);

        // Libérer le créneau
        if ($appointment->timeSlot) {
            $appointment->timeSlot->update(['is_booked' => false]);
        }

        return response()->json([
            'message' => 'Rendez-vous annulé avec succès'
        ]);
    }

    /**
     * Rendez-vous en attente
     *
     * @authenticated
     * @group Rendez-vous (Patient)
     */
    public function pending()
    {
        return $this->byStatus('pending');
    }

    /**
     * Rendez-vous confirmés
     *
     * @authenticated
     * @group Rendez-vous (Patient)
     */
    public function confirmed()
    {
        return $this->byStatus('confirmed');
    }

    /**
     * Rendez-vous passés
     *
     * @authenticated
     * @group Rendez-vous (Patient)
     */
    public function past()
    {
        return Appointment::with(['doctor', 'timeSlot', 'reason'])
            ->where('user_id', Auth::id())
            ->where('date', '<', now()->toDateString())
            ->get();
    }

    /**
     * Filtre par statut
     */
    private function byStatus(string $status)
    {
        return response()->json(
            Appointment::with(['doctor', 'timeSlot', 'reason'])
                ->where('user_id', Auth::id())
                ->where('status', $status)
                ->get()
        );
    }

    /**
     * Sécurité : le rendez-vous appartient bien au patient
     */
    private function authorizeAppointment(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }
    }
}
