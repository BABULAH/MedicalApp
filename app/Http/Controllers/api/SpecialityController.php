<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Speciality;
use Illuminate\Http\Request;

class SpecialityController extends Controller
{
    /**
     * Liste des spécialités médicales
     *
     * Retourne toutes les spécialités disponibles.
     *
     * @group Données publiques
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "name": "Cardiologie",
     *     "description": "Spécialité du cœur",
     *     "created_at": "2025-01-01T10:00:00.000000Z",
     *     "updated_at": "2025-01-01T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index()
    {
        return response()->json(
            Speciality::orderBy('name')->get()
        );
    }

    /**
     * Détails d’une spécialité
     *
     * @group Données publiques
     *
     * @urlParam speciality int required ID de la spécialité
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Cardiologie",
     *   "description": "Spécialité du cœur"
     * }
     */
    public function show(Speciality $speciality)
    {
        return response()->json($speciality);
    }

    /**
     * Créer une spécialité (Admin)
     *
     * @authenticated
     * @group Administration - Spécialités
     *
     * @bodyParam name string required Nom de la spécialité
     * @bodyParam description string Description optionnelle
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:specialities,name',
            'description' => 'nullable|string',
        ]);

        $speciality = Speciality::create($data);

        return response()->json([
            'message' => 'Spécialité créée avec succès',
            'speciality' => $speciality
        ], 201);
    }

    /**
     * Mettre à jour une spécialité (Admin)
     *
     * @authenticated
     * @group Administration - Spécialités
     */
    public function update(Request $request, Speciality $speciality)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:specialities,name,' . $speciality->id,
            'description' => 'nullable|string',
        ]);

        $speciality->update($data);

        return response()->json([
            'message' => 'Spécialité mise à jour avec succès',
            'speciality' => $speciality
        ]);
    }

    /**
     * Supprimer une spécialité (Admin)
     *
     * @authenticated
     * @group Administration - Spécialités
     */
    public function destroy(Speciality $speciality)
    {
        $speciality->delete();

        return response()->json([
            'message' => 'Spécialité supprimée avec succès'
        ]);
    }
}
