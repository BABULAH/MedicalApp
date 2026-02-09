<?php

namespace App\Http\Controllers\Api\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Profil du médecin connecté
     *
     * @authenticated
     * @group Médecin - Profil
     *
     * @response 200 {
     *   "id": 1,
     *   "first_name": "Amadou",
     *   "last_name": "Diop",
     *   "email": "doctor@example.com",
     *   "speciality_id": 2,
     *   "establishment_id": 1,
     *   "locality_id": 3,
     *   "phone": "+221700000000",
     *   "photo": null,
     *   "bio": "Cardiologue expérimenté",
     *   "experience_years": 10,
     *   "consultation_price": 20000,
     *   "emergency_price": 50000,
     *   "status": "active"
     * }
     */
    public function show()
    {
        return response()->json(Auth::user());
    }

    /**
     * Mettre à jour le profil du médecin
     *
     * @authenticated
     * @group Médecin - Profil
     *
     * @bodyParam first_name string required
     * @bodyParam last_name string required
     * @bodyParam phone string
     * @bodyParam email string required
     * @bodyParam bio string
     * @bodyParam speciality_id int
     * @bodyParam establishment_id int
     * @bodyParam locality_id int
     * @bodyParam consultation_price numeric
     * @bodyParam emergency_price numeric
     * @bodyParam status string
     * @bodyParam photo file
     */
    public function update(Request $request)
    {
        $doctor = Auth::user();

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:doctors,email,' . $doctor->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'speciality_id' => 'nullable|exists:specialities,id',
            'establishment_id' => 'nullable|exists:establishments,id',
            'locality_id' => 'nullable|exists:localities,id',
            'consultation_price' => 'nullable|numeric',
            'emergency_price' => 'nullable|numeric',
            'status' => 'nullable|in:active,inactive,suspended',
            'photo' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $path = $file->store('doctor_photos', 'public');
            $data['photo'] = $path;
        }

        $doctor->update($data);

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'doctor' => $doctor
        ]);
    }
}
