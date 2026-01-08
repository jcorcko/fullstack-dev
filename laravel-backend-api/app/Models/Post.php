<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ==================== RELACIONES ====================

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope: Solo posts publicados
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope: Solo posts sin publicar (draft)
     */
    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('is_published', false);
    }

    /**
     * Scope: Buscar por slug
     */
    public function scopeBySlug(Builder $query, string $slug): Builder
    {
        return $query->where('slug', $slug);
    }

    /**
     * Scope: Posts de una categoría
     */
    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope: Posts de un autor
     */
    public function scopeByAuthor(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Posts recientes primero
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    /**
     * Scope: Posts con imagen
     */
    public function scopeWithImage(Builder $query): Builder
    {
        return $query->whereNotNull('image');
    }

    // ==================== MUTADORES ====================

    /**
     * Auto-generar slug desde título si no se proporciona
     */
    protected function slug(): Attribute
    {
        return Attribute::make(
            set: fn(?string $value) => $value ?? Str::slug($this->attributes['title'] ?? ''),
        );
    }
}
