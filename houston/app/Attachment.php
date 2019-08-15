<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $casts = [
        'content' => 'array',
    ];

    //
    protected $fillable = [

        'media_id', 'content', 'upload_session',
    ];

    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
