<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    // Just Can Update & insert
    protected $fillable = [
        'read',
        'name',
        'email',
        'title',
        'message',
    ];

    public function unReadMessages()
    {
        return $this->where('read', false)->get(['id', 'name', 'email', 'title', 'created_at']);
    }
}
