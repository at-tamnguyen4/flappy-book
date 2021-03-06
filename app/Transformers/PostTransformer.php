<?php

namespace App\Transformers;

use App\Model\Post;
use League\Fractal\TransformerAbstract;
use League\Fractal\Serializer\ArraySerializer;
use League\Fractal\Serializer\DataArraySerializer;
use Illuminate\Support\Facades\App;

class PostTransformer extends TransformerAbstract
{
    /**
     * The attributes that are available include.
     *
     * @var array
     */
    protected $availableIncludes = [
        'user',
        'rating'
    ];

    /**
     * Transform
     *
     * @param Post $post post
     *
     * @return Array
     */
    public function transform(Post $post)
    {
        return [
            'id' => (int) $post->id,
            'content' => (string) $post->content,
            'status' => (int) $post->status,
            'user_id' => (int) $post->user_id,
            'created_at' => (string) $post->created_at,
            'updated_at' => (string) $post->updated_at
        ];
    }

    /**
     * Transform
     *
     * @param Post $post post
     *
     * @return Item
     */
    public function includeUser(Post $post)
    {
        return $this->item($post->users, App::make(UserTransformer::class));
    }

    /**
     * Transform
     *
     * @param Post $post post
     *
     * @return Item
     */
    public function includeRating(Post $post)
    {
        if (!$post->rating) {
            return null;
        }

        return $this->item($post->rating, App::make(RatingTransformer::class));
    }
}
