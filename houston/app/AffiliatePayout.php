<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffiliatePayout extends Model
{
    protected $fillable = ['user_id', 'requested_amount', 'paid_date', 'ip'];
}
