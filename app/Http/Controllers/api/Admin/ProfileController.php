<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Profil de l’administrateur connecté
     *
     * @authenticated
     * @group Administration - Profil
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Admin",
     *   "email": "admin@example.com",
     *   "role": "admin"
     * }
     */
    public function show()
    {
        return response()->json(Auth::user());
    }

    /**
     * Mettre à jour le profil administrateur
     *
     * @authenticated
     * @group Administration - Profil
     *
     * @bodyParam name string Nom complet
     * @bodyParam email string Email
     * @bodyParam password string Mot de passe (optionnel)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
        ]);

        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profil administrateur mis à jour avec succès',
            'user' => $user
        ]);
    }
}
