<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['slug', 'name', 'icon', 'order'];

    public static function order()
    {
        return self::orderBy('order', 'asc')->get();
    }

    // Category Media Relationship
    public function media()
    {
        return $this->hasMany(Media::class);
    }

    // Category Media Relationship
    public function mediaMostPopular()
    {
        return $this->hasMany(Media::class)->orderBy('views', 'desc');
    }

    // Category Media Relationship
    public function mediaMostLikes()
    {
        return $this->hasMany(Media::class)->withCount('likes')->orderBy('likes_count', 'desc');

        /*return $this->hasMany(Media::class)
            ->leftJoin('likeable_like_counters','likeable_like_counters.likable_id','=','media.id')
            ->orderBy('count','desc')
            ->select(
                'media.*',
                'media.id',
                'media.title',
                'media.user_id',
                'media.category_id',
                'media.title',
                'media.created_at',
                'media.views',
                'likeable_like_counters.count');*/
    }

    // Category Media Relationship
    public function mediaMostComments()
    {
        return $this->hasMany(Media::class)->withCount('comments')->orderBy('comments_count', 'desc');
    }
}
