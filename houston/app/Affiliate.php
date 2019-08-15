<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable = ['user_id', 'status', 'ip', 'adblock_ask', 'adblock_off'];
}
