<?php

namespace App\Http\Controllers;

use App\Page;

class PageController extends Controller
{
    //
    public function show($parent_slug, $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        if ($page->content) {
            return view('pages.page', compact('page'));
        } elseif(view()->exists('pages.'.$slug)) {
            return view('pages.'.$slug)->render();
        }

        return view('pages.page', compact('page'));
    }
}
