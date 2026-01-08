<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    /**
     * Handle the Post "deleting" event.
     * Eliminar archivo de imagen asociado al post.
     */
    public function deleting(Post $post): void
    {
        if ($post->image && Storage::exists('public/posts/' . $post->image)) {
            Storage::delete('public/posts/' . $post->image);
        }
    }
}
