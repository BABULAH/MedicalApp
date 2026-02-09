<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Locality;
use Illuminate\Http\Request;

class LocalityController extends Controller
{
    /**
     * Liste des localités
     *
     * Retourne toutes les localités disponibles.
     *
     * @group Données publiques
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "name": "Guédiawaye",
     *     "created_at": "2025-01-01T10:00:00.000000Z",
     *     "updated_at": "2025-01-01T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index()
    {
        return response()->json(
            Locality::orderBy('name')->get()
        );
    }

    /**
     * Détails d’une localité
     *
     * @group Données publiques
     *
     * @urlParam locality int required ID de la localité
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Guédiawaye"
     * }
     */
    public function show(Locality $locality)
    {
        return response()->json($locality);
    }

    /**
     * Créer une localité (Admin)
     *
     * @authenticated
     * @group Administration - Localités
     *
     * @bodyParam name string required Nom de la localité
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:localities,name',
        ]);

        $locality = Locality::create($data);

        return response()->json([
            'message' => 'Localité créée avec succès',
            'locality' => $locality
        ], 201);
    }

    /**
     * Mettre à jour une localité (Admin)
     *
     * @authenticated
     * @group Administration - Localités
     */
    public function update(Request $request, Locality $locality)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:localities,name,' . $locality->id,
        ]);

        $locality->update($data);

        return response()->json([
            'message' => 'Localité mise à jour avec succès',
            'locality' => $locality
        ]);
    }

    /**
     * Supprimer une localité (Admin)
     *
     * @authenticated
     * @group Administration - Localités
     */
    public function destroy(Locality $locality)
    {
        $locality->delete();

        return response()->json([
            'message' => 'Localité supprimée avec succès'
        ]);
    }
}
        