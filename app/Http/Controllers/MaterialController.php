<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MaterialController extends Controller
{
    //Equipo 1 Heinner Villagra Rostran
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'unidadMedida' => 'required|string|max:255',
                'descripcion'  => 'required|string|max:255',
                'ubicacion'    => 'required|string|max:255',
                'idCategoria'  => 'required|integer|exists:categorias,idCategoria',
            ]);

            $material = Material::create($validated);

            return response()->json([
                'message'  => 'Material creado exitosamente.',
                'material' => $material->load('categoria'),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación.',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    public function update(Request $request, int $codigo): JsonResponse
    {
        $material = Material::find($codigo);

        if (!$material) {
            return response()->json([
                'message' => 'Material no encontrado.',
            ], 404);
        }

        try {
            $validated = $request->validate([
                'unidadMedida' => 'sometimes|string|max:255',
                'descripcion'  => 'sometimes|string|max:255',
                'ubicacion'    => 'sometimes|string|max:255',
                'idCategoria'  => 'sometimes|integer|exists:categorias,idCategoria',
            ]);

            $material->update($validated);

            return response()->json([
                'message'  => 'Material actualizado exitosamente.',
                'material' => $material->load('categoria'),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Error de validación.',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    public function index(): JsonResponse
    {
        $materiales = Material::with('categoria')->get();

        return response()->json([
            'message'    => 'Lista de materiales obtenida exitosamente.',
            'materiales' => $materiales,
        ], 200);
    }

}