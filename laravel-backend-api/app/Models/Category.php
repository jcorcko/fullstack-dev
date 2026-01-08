<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Scope: Buscar categoría por slug
     */
    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    /**
     * Scope: Obtener solo categorías con posts publicados
     */
    public function scopeWithPublishedPosts(Builder $query): Builder
    {
        return $query->whereHas('posts', function ($q) {
            $q->where('is_published', true);
        });
    }
}
