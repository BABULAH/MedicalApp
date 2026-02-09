<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API Rendez-vous Médicaux",
 *      description="Documentation Swagger pour l'API",
 * )
 */
class SwaggerTestController extends Controller
{
    /**
     * @OA\Get(
     *      path="/api/test",
     *      operationId="testApi",
     *      tags={"Test"},
     *      summary="Endpoint test",
     *      description="Un endpoint test pour Swagger",
     *      @OA\Response(
     *          response=200,
     *          description="Succès"
     *      )
     * )
     */
    public function test()
    {
        return response()->json(['message' => 'Swagger fonctionne !']);
    }
}
