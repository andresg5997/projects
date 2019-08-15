<?php

namespace App\Http\Controllers;

use App\Media;

class TagController extends Controller
{
    //
    public function show($slug)
    {
        $menu = 'tag';

        $tag = $slug;

        $media = Media::withAnyTag([$slug])->where('private', 0)->latest()->paginate(config('media_per_page'));

        return view('home', compact('media', 'menu', 'tag'));
    }
}
