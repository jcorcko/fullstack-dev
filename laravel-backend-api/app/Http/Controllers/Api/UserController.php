<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Listar todos los usuarios (con paginación)
    public function index()
    {
        $users = User::paginate(15);
        return UserResource::collection($users);
    }

    // Mostrar un usuario específico
    public function show($id)
    {
        $user = User::findOrFail($id);
        return new UserResource($user);
    }

    // Crear un nuevo usuario
    public function store(Request $request)
    {
        try {
            // Validar los datos del request
            $data = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:6',
            ]);

            // Nota: Password se hashea automáticamente via User model cast

            // Crear el nuevo usuario
            $user = User::create($data);

            // Devolvemos el recurso creado con una respuesta JSON
            return response()->json(new UserResource($user), 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si la validación falla, devolvemos un JSON con los errores
            return response()->json([
                'message' => 'Datos de validación inválidos',
                'errors' => $e->errors(), // Los errores de validación
            ], 422);
        }
    }


    // Actualizar un usuario existente
    public function update(Request $request, $id)
    {
        try {
            // Buscar al usuario o devolver un error 404 si no se encuentra
            $user = User::findOrFail($id);

            // Validar los datos del request
            $data = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
                'password' => 'sometimes|required|string|min:6',
            ]);

            // Nota: Password se hashea automáticamente via User model cast si está presente

            // Actualizar el usuario con los datos validados
            $user->update($data);

            // Devolver el recurso actualizado
            return new UserResource($user);        
        } catch (\Exception $e) {
            // Capturar cualquier otro error inesperado
            return response()->json([
                'message' => 'Error al procesar la solicitud',
                'error' => $e->errors(),
            ], 422);
        }
    }

    // Eliminar un usuario
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
