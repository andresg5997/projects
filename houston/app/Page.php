<?php

namespace App;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    // Just Can Update & insert
    protected $fillable = [
        'slug',
        'order',
        'icon',
        'name',
        'title',
        'content',
        'parent',
        'footer',
    ];

    public static function order()
    {
        $footer_order_key = 'footer_order_key';

        if (Cache::has($footer_order_key)) {
            $page = Cache::get($footer_order_key);
        } else {
            $page = self::where('footer', 0)->orderBy('order', 'asc')->get(['name', 'slug', 'icon']);

            Cache::put($footer_order_key, $page, config('expires_at_interval'));
        }


        return $page;
    }

    public static function orderFooter($parent_id)
    {
        $footer_orderFooter_key = 'footer_'.$parent_id.'_orderFooter_key';

        if (Cache::has($footer_orderFooter_key)) {
            $page = Cache::get($footer_orderFooter_key);
        } else {
            $page = self::where('footer', 1)
                ->where('parent', $parent_id)
                ->orderBy('order', 'asc')
                ->get(['name', 'slug', 'id']);

            Cache::put($footer_orderFooter_key, $page, config('expires_at_interval'));
        }

        return $page;
    }
}
