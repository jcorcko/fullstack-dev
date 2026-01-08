<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Listar todas las categorías (con paginación)
    public function index()
    {
        $categories = Category::with('posts')->paginate(15);
        return CategoryResource::collection($categories);
    }

    // Mostrar una categoría específica
    public function show($id)
    {
        $category = Category::with('posts')->findOrFail($id);
        return new CategoryResource($category);
    }

    // Crear una nueva categoría
    public function store(Request $request)
    {
        try{
            // Validar los datos del request
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:categories,name',
                'slug' => 'required|string|max:255|unique:categories,slug',
                'description' => 'nullable|string',
            ]);

            // Crear la nueva categoría
            $category = Category::create($data);

            // Devolvemos el recurso creado con una respuesta JSON
            return response()->json(new CategoryResource($category), 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si la validación falla, devolvemos un JSON con los errores
            return response()->json([
                'message' => 'Datos de validación inválidos',
                'errors' => $e->errors(), // Los errores de validación
            ], 422);
        }        
    }

    // Actualizar categoría existente
    public function update(Request $request, $id)
    {
        try{
            $category = Category::findOrFail($id);

            $data = $request->validate([
                'name' => ['sometimes','required','string','max:255', Rule::unique('categories')->ignore($category->id)],
                'slug' => ['sometimes','required','string','max:255', Rule::unique('categories')->ignore($category->id)],
                'description' => 'nullable|string',
            ]);

            $category->update($data);

            return new CategoryResource($category->load('posts'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si la validación falla, devolvemos un JSON con los errores
            return response()->json([
                'message' => 'Datos de validación inválidos',
                'errors' => $e->errors(), // Los errores de validación
            ], 422);
        }
    }

    // Eliminar una categoría
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(null, 204);
    }
}
