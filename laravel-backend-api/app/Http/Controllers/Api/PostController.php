<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    // Listar todos los posts con categoría y autor (con paginación)
    public function index()
    {
        $posts = Post::with(['category', 'user'])->paginate(15);
        return PostResource::collection($posts);
    }

    // Mostrar un post específico
    public function show($id)
    {
        $post = Post::with(['category', 'user'])->findOrFail($id);
        return new PostResource($post);
    }

    // Crear un nuevo post
    public function store(Request $request)
    {
        try{
            // Validar los datos del request
            $data = $request->validate([
                'title' => 'required|string|max:255',
                'slug' => 'required|string|max:255|unique:posts',
                'category_id' => 'required|exists:categories,id',
                'user_id' => 'required|exists:users,id',
                'excerpt' => 'nullable|string',
                'body' => 'required|string',
                'is_published' => 'boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Subir imagen si existe
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/posts', $filename);
                $data['image'] = $filename;
            }

            // Crear el nuevo post
            $post = Post::create($data);

            // Devolvemos el recurso creado con una respuesta JSON
            return response()->json(new PostResource($post->load(['category', 'user'])), 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si la validación falla, devolvemos un JSON con los errores
            return response()->json([
                'message' => 'Datos de validación inválidos',
                'errors' => $e->errors(), // Los errores de validación
            ], 422);
        }
    }

    // Actualizar un post existente
    public function update(Request $request, $id)
    {
        try {
            // Buscar el post o devolver un error 404 si no se encuentra
            $post = Post::findOrFail($id);

            // Validar los datos del request
            $data = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'slug' => ['sometimes','required','string','max:255', Rule::unique('posts')->ignore($post->id)],
                'category_id' => 'sometimes|required|exists:categories,id',
                'user_id' => 'sometimes|required|exists:users,id',
                'excerpt' => 'nullable|string',
                'body' => 'sometimes|required|string',
                'is_published' => 'boolean',
                'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Subir nueva imagen si existe
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/posts', $filename);
                $data['image'] = $filename;

                // Eliminar imagen anterior si existe
                if ($post->image && Storage::exists('public/posts/' . $post->image)) {
                    Storage::delete('public/posts/' . $post->image);
                }
            }

            // Actualizar el post con los datos validados
            $post->update($data);

            // Devolver el recurso actualizado
            return new PostResource($post->load(['category', 'user']));
    } catch (\Illuminate\Validation\ValidationException $e) {
            // Si la validación falla, devolvemos un JSON con los errores
            return response()->json([
                'message' => 'Datos de validación inválidos',
                'errors' => $e->errors(), // Los errores de validación
            ], 422);
        }
    }

    // Eliminar un post
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        // Eliminar imagen si existe
        if ($post->image && Storage::exists('public/posts/' . $post->image)) {
            Storage::delete('public/posts/' . $post->image);
        }

        $post->delete();

        return response()->json(null, 204);
    }
}
