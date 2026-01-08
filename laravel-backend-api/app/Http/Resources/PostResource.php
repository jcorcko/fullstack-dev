<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'is_published' => $this->is_published,
            'image' => $this->image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        // Solo incluir category si fue explícitamente cargado (evita N+1 queries)
        if ($this->relationLoaded('category')) {
            $data['category'] = new CategoryResource($this->category);
        }

        // Solo incluir author si fue explícitamente cargado (evita N+1 queries)
        if ($this->relationLoaded('user')) {
            $data['author'] = new UserResource($this->user);
        }

        return $data;
    }
}
