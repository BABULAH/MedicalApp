<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StoreAppointmentRequest;
use App\Http\Resources\Patient\AppointmentResource;
use App\Services\Patient\AppointmentService;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $service
    ) {}

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = $this->service->create($request->validated());

        return response()->json([
            'message' => 'Rendez-vous demandé avec succès.',
            'data' => new AppointmentResource($appointment)
        ], 201);
    }
}