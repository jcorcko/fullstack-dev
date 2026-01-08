<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // ==================== RELACIONES ====================

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // ==================== SCOPES ====================

    /**
     * Scope: Usuarios activos
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Usuarios inactivos
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope: Usuarios verificados
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('email_verified_at');
    }

    /**
     * Scope: Usuarios sin verificar
     */
    public function scopeUnverified(Builder $query): Builder
    {
        return $query->whereNull('email_verified_at');
    }

    /**
     * Scope: Buscar por email
     */
    public function scopeByEmail(Builder $query, string $email): Builder
    {
        return $query->where('email', $email);
    }

    /**
     * Scope: Búsqueda por nombre (parcial)
     */
    public function scopeSearchByName(Builder $query, string $name): Builder
    {
        return $query->where('name', 'like', "%{$name}%");
    }

    /**
     * Scope: Usuarios con posts publicados
     */
    public function scopeWithPublishedPosts(Builder $query): Builder
    {
        return $query->whereHas('posts', function ($q) {
            $q->where('is_published', true);
        });
    }

    /**
     * Scope: Usuarios con al menos un post
     */
    public function scopeWithPosts(Builder $query): Builder
    {
        return $query->whereHas('posts');
    }

    /**
     * Scope: Usuarios recientes
     */
    public function scopeLatest(Builder $query): Builder
    {
        return $query->orderByDesc('created_at');
    }

    // ==================== MUTADORES ====================

    /**
     * Normalizar email a minúsculas
     */
    protected function email(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => strtolower(trim($value)),
        );
    }

    /**
     * Trimear nombre
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => trim($value),
        );
    }
}
