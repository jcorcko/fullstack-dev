<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    // Listar todas las categorías
    public function index()
    {
        $categories = Category::with('posts')->get();
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
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string',
        ]);

        $category = Category::create($data);

        return new CategoryResource($category->load('posts'));
    }

    // Actualizar categoría existente
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $data = $request->validate([
            'name' => ['sometimes','required','string','max:255', Rule::unique('categories')->ignore($category->id)],
            'slug' => ['sometimes','required','string','max:255', Rule::unique('categories')->ignore($category->id)],
            'description' => 'nullable|string',
        ]);

        $category->update($data);

        return new CategoryResource($category->load('posts'));
    }

    // Eliminar una categoría
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(null, 204);
    }
}
