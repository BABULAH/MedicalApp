<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\ListDoctorAvailabilityRequest;
use App\Http\Resources\Patient\AvailabilityResource;
use App\Services\Patient\AvailabilityService;
use Illuminate\Http\JsonResponse;

class DoctorAvailabilityController extends Controller
{
    public function __construct(private AvailabilityService $service)
    {
    }

    /**
     * Liste les disponibilités d'un médecin
     */
    public function index(ListDoctorAvailabilityRequest $request): JsonResponse
    {
        $doctorId = $request->validated()['doctor_id'];

        $availabilities = $this->service->getDoctorAvailabilities($doctorId);

        if ($availabilities->isEmpty()) {
            return response()->json([
                'message' => 'Ce médecin n\'a aucune disponibilité pour le moment.'
            ], 200);
        }

        return response()->json([
            'data' => AvailabilityResource::collection($availabilities)
        ], 200);
    }
}