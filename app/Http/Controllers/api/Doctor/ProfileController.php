<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Doctor\UpdateProfileRequest;
use App\Http\Resources\Doctor\ProfileResource;
use App\Services\Doctor\ProfileService;

class ProfileController extends Controller
{
    public function __construct(private readonly ProfileService $service) {}

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();        // récupère l'utilisateur connecté
        $doctor = $user->doctor;         // récupère le modèle Doctor lié

        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Le profil médecin n’existe pas pour cet utilisateur.'
            ], 404);
        }

        $updatedDoctor = $this->service->updateProfile($doctor, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès.',
            'data' => new ProfileResource($updatedDoctor),
        ]);
    }
}