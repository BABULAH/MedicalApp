<?php

namespace App\Http\Controllers\Api\Patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\ListDoctorRequest;
use App\Http\Resources\Patient\DoctorResource;
use App\Services\Patient\DoctorService;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function __construct(
        private DoctorService $service
    ) {}

    public function index(ListDoctorRequest $request)
    {
        $doctors = $this->service->list($request->validated());

        return DoctorResource::collection($doctors);
    }

    public function show(Doctor $doctor)
    {
        $doctor = $this->service->show($doctor);
        $doctor->load('availabilities.timeSlots');

        return new DoctorResource($doctor);
    }
}