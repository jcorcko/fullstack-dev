<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     * Asegurar que nuevos usuarios se crean como activos.
     */
    public function created(User $user): void
    {
        // Los nuevos usuarios se crean como activos por defecto
        if (is_null($user->is_active)) {
            $user->update(['is_active' => true]);
        }
    }

    /**
     * Handle the User "deleting" event.
     * Validar que no se borre un usuario con posts publicados.
     */
    public function deleting(User $user): void
    {
        $publishedPostsCount = $user->posts()
            ->where('is_published', true)
            ->count();

        if ($publishedPostsCount > 0) {
            throw new \Exception(
                "No se puede eliminar el usuario '{$user->name}' porque tiene {$publishedPostsCount} post(s) publicado(s). "
                . "Primero despublica o elimina los posts."
            );
        }
    }
}
