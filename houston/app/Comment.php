<?php

namespace App;

use Conner\Likeable\LikeableTrait;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use LikeableTrait;
    protected $fillable = ['media_id', 'post_id', 'body', 'approved', 'user_id'];

    //
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //
    public function media()
    {
        return $this->belongsTo(Media::class);
    }

    // Comment Flags Relationship
    public function flags()
    {
        return $this->hasMany(Flag::class, 'flagged_id')->where('type', 'comment');
    }

    // Comment flags count

    public function flagsCount()
    {
        return $this->flags()->count();
    }

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
