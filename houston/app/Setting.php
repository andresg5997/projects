<?php

namespace App;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //
    protected $fillable = ['name', 'attributes'];

    public function gets($name)
    {
        $attributes_key = 'attributes_'.$name;

        if (Cache::has($attributes_key)) {
            $attributes = Cache::get($attributes_key);
        } else {
            $attributes = $this->where('name', $name)->value('attributes');

            Cache::put($attributes_key, $attributes, config('expires_at_interval'));
        }

        if ($attributes) {
            return json_decode($attributes);
        }

        return false;
    }
}
