<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    //
    protected $fillable = ['user_id', 'type', 'flagged_id', 'reason'];

    public function media()
    {
        return $this->belongsTo(Media::class, 'flagged_id');
    }

    public function flaggedMedia()
    {
        return $this->media()->where('type', 'media');
    }

    public function flaggedComments()
    {
        return $this->media()->where('type', 'comment');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
