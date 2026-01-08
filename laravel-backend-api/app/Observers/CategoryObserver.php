<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    /**
     * Handle the Category "deleting" event.
     * Validar que no se borre una categoría con posts publicados.
     */
    public function deleting(Category $category): void
    {
        // Contar posts publicados asociados a esta categoría
        $publishedPostsCount = $category->posts()
            ->where('is_published', true)
            ->count();

        if ($publishedPostsCount > 0) {
            throw new \Exception(
                "No se puede eliminar la categoría '{$category->name}' porque tiene {$publishedPostsCount} post(s) publicado(s). "
                . "Despublica o elimina los posts primero."
            );
        }
    }
}
