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
    // Listar todos los posts con categoría y autor
    public function index()
    {
        $posts = Post::with(['category', 'user'])->get();
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

        $post = Post::create($data);

        return new PostResource($post->load(['category', 'user']));
    }

    // Actualizar un post existente
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

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

        $post->update($data);

        return new PostResource($post->load(['category', 'user']));
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
