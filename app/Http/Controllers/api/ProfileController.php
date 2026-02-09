<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user());
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'nullable|string',
        ]);

        $request->user()->update($request->only('name', 'phone'));

        return response()->json([
            'message' => 'Profil mis à jour',
            'user' => $request->user(),
        ]);
    }
}
