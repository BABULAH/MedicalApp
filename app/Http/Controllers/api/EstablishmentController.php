<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Establishment;
use Illuminate\Http\Request;

class EstablishmentController extends Controller
{
    /**
     * Liste des établissements médicaux
     *
     * Hôpitaux, cliniques et cabinets médicaux.
     *
     * @group Données publiques
     *
     * @response 200 [
     *   {
     *     "id": 1,
     *     "name": "Hôpital Principal de Dakar",
     *     "type": "Hôpital",
     *     "address": "Avenue Nelson Mandela, Dakar",
     *     "locality_id": 3,
     *     "created_at": "2025-01-01T10:00:00.000000Z",
     *     "updated_at": "2025-01-01T10:00:00.000000Z"
     *   }
     * ]
     */
    public function index()
    {
        return response()->json(
            Establishment::with('locality')
                ->orderBy('name')
                ->get()
        );
    }

    /**
     * Détails d’un établissement
     *
     * @group Données publiques
     *
     * @urlParam establishment int required ID de l’établissement
     *
     * @response 200 {
     *   "id": 1,
     *   "name": "Clinique du Cap",
     *   "type": "Clinique",
     *   "address": "Dakar Plateau",
     *   "locality_id": 1
     * }
     */
    public function show(Establishment $establishment)
    {
        return response()->json(
            $establishment->load('locality')
        );
    }

    /**
     * Créer un établissement (Admin)
     *
     * @authenticated
     * @group Administration - Établissements
     *
     * @bodyParam name string required Nom de l’établissement
     * @bodyParam type string required (Hôpital, Clinique, Cabinet)
     * @bodyParam address string required Adresse
     * @bodyParam locality_id int required ID de la localité
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:establishments,name',
            'type' => 'required|string',
            'address' => 'required|string',
            'locality_id' => 'required|exists:localities,id',
        ]);

        $establishment = Establishment::create($data);

        return response()->json([
            'message' => 'Établissement créé avec succès',
            'establishment' => $establishment
        ], 201);
    }

    /**
     * Mettre à jour un établissement (Admin)
     *
     * @authenticated
     * @group Administration - Établissements
     */
    public function update(Request $request, Establishment $establishment)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:establishments,name,' . $establishment->id,
            'type' => 'required|string',
            'address' => 'required|string',
            'locality_id' => 'required|exists:localities,id',
        ]);

        $establishment->update($data);

        return response()->json([
            'message' => 'Établissement mis à jour avec succès',
            'establishment' => $establishment
        ]);
    }

    /**
     * Supprimer un établissement (Admin)
     *
     * @authenticated
     * @group Administration - Établissements
     */
    public function destroy(Establishment $establishment)
    {
        $establishment->delete();

        return response()->json([
            'message' => 'Établissement supprimé avec succès'
        ]);
    }
}
