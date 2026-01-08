<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Solo incluir posts si fueron explícitamente cargados (evita N+1 queries)
        if ($this->relationLoaded('posts')) {
            $data['posts_count'] = $this->posts()->count();
            $data['posts'] = PostResource::collection($this->posts);
        }

        return $data;
    }
}
