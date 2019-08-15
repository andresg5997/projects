<?php

namespace App;

use Conner\Likeable\LikeableTrait;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use LikeableTrait;

    protected $fillable = ['subject', 'content', 'user_id', 'team_id', 'media_ids'];

    protected $guarded = ['id'];

    public function media(){
        return $this->hasMany(Media::class);
    }

    public function team(){
        return $this->belongsTo(Team::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function userName(){
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
