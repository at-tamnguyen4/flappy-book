<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Value of review post
     */

    const REVIEW_TYPE = 0;
    /**
     * Value of status post
     */

    const STATUS_TYPE = 1;
    /**
     * Value of find book post
     */
    
    const FIND_TYPE = 2;
    /**
     * Declare table
     *
     * @var string $tabel table name
     */
    protected $table = 'posts';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'content',
        'is_findbook',
    ];
    
    /**
     * Relationship belongsTo with User
     *
     * @return array
     */
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relationship hasMany with Like
     *
     * @return array
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relationship morphMany with Comment
     *
     * @return array
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
