<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Http\Resources\BlogResource;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'published');
        $allowedStatuses = ['draft', 'published', 'all'];

        if (!in_array($status, $allowedStatuses, true)) {
            $status = 'published';
        }

        if (!$request->user()) {
            $status = 'published';
        }

        $query = Blog::query()->with(['author:id,name,email', 'categories:id,name,slug']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $blogs = $query
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 10))
            ->withQueryString();

        return BlogResource::collection($blogs);
    }

    public function show(Blog $blog, Request $request)
    {
        if ($blog->status !== 'published' && !$request->user()) {
            abort(404);
        }

        $blog->load(['author:id,name,email', 'categories:id,name,slug']);

        return new BlogResource($blog);
    }

    public function store(StoreBlogRequest $request)
    {
        $data = $request->validated();
        $categoryIds = $data['category_ids'] ?? [];
        unset($data['category_ids']);
        $data['user_id'] = $request->user()->id;
        $data['slug'] = $this->generateUniqueSlug($data['slug'] ?? $data['title']);
        $data = $this->applyPublishFields($data);

        $blog = Blog::create($data);
        if (!empty($categoryIds)) {
            $blog->categories()->sync($categoryIds);
        }
        $blog->load(['author:id,name,email', 'categories:id,name,slug']);

        return (new BlogResource($blog))
            ->response()
            ->setStatusCode(201);
    }

    public function update(UpdateBlogRequest $request, Blog $blog)
    {
        $data = $request->validated();
        $categoryIds = $data['category_ids'] ?? null;
        unset($data['category_ids']);

        if (array_key_exists('slug', $data)) {
            $data['slug'] = $data['slug']
                ? $this->generateUniqueSlug($data['slug'], $blog->id)
                : $this->generateUniqueSlug($data['title'] ?? $blog->title, $blog->id);
        } elseif (array_key_exists('title', $data)) {
            $data['slug'] = $this->generateUniqueSlug($data['title'], $blog->id);
        }

        $data = $this->applyPublishFields($data);

        $blog->update($data);
        if (is_array($categoryIds)) {
            $blog->categories()->sync($categoryIds);
        }
        $blog->load(['author:id,name,email', 'categories:id,name,slug']);

        return new BlogResource($blog);
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        return response()->json(null, 204);
    }

    private function generateUniqueSlug(string $value, ?int $ignoreId = null): string
    {
        $slug = Str::slug($value);

        if ($slug === '') {
            $slug = Str::random(8);
        }

        $base = $slug;
        $counter = 1;

        while (
            Blog::query()
                ->where('slug', $slug)
                ->when($ignoreId, function ($query) use ($ignoreId) {
                    $query->where('id', '!=', $ignoreId);
                })
                ->exists()
        ) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    private function applyPublishFields(array $data): array
    {
        if (array_key_exists('status', $data)) {
            if ($data['status'] === 'published') {
                if (empty($data['published_at'])) {
                    $data['published_at'] = now();
                }
            } else {
                $data['published_at'] = null;
            }
        } elseif (array_key_exists('published_at', $data) && $data['published_at']) {
            $data['status'] = 'published';
        }

        return $data;
    }
}
