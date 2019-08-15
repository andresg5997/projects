<?php

namespace App\Http\Controllers;

use App\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        view()->share('website_desc', config('website_desc'));
        view()->share('website_keywords', config('website_keywords'));
    }

    //
    public function index()
    {
        $categories = Category::latest();

        return view('categories', compact('categories'));
    }

    public function show($slug)
    {
        $menu = 'recent';

        $category = Category::where('slug', $slug)->firstOrFail();

        $media = $category->media()
            ->latest()
            ->where('approved', 1)
            ->where('private', 0)
            ->where('type', '!=', 'text')
            ->where('type', '!=', 'application')
            ->paginate(config('media_per_page'));

        return view('category', compact('category', 'media', 'menu'));
    }

    public function popular($slug)
    {
        $menu = 'popular';

        $category = Category::where('slug', $slug)->firstOrFail();

        $media = Category::where('slug', $slug)
            ->firstOrFail()
            ->mediaMostPopular()
            ->where('approved', 1)
            ->where('private', 0)
            ->where('type', '!=', 'text')
            ->where('type', '!=', 'application')
            ->paginate(config('media_per_page'));

        return view('category', compact('category', 'media', 'menu'));
    }

    public function likes($slug)
    {
        $menu = 'likes';

        $category = Category::where('slug', $slug)->firstOrFail();

        $media = Category::where('slug', $slug)
            ->firstOrFail()
            ->mediaMostLikes()
            ->where('approved', 1)
            ->where('private', 0)
            ->where('type', '!=', 'text')
            ->where('type', '!=', 'application')
            ->paginate(config('media_per_page'));

        return view('category', compact('category', 'media', 'menu'));
    }

    public function comments($slug)
    {
        $menu = 'comments';

        $category = Category::where('slug', $slug)->firstOrFail();

        $media = Category::where('slug', $slug)
            ->firstOrFail()
            ->mediaMostComments()
            ->where('approved', 1)
            ->where('private', 0)
            ->where('type', '!=', 'text')
            ->where('type', '!=', 'application')
            ->paginate(config('media_per_page'));

        return view('category', compact('category', 'media', 'menu'));
    }

    public function random($slug)
    {
        $menu = 'random';

        $category = Category::where('slug', $slug)->firstOrFail();

        $media = Category::where('slug', $slug)
            ->firstOrFail()
            ->media()
            ->where('approved', 1)
            ->where('private', 0)
            ->where('type', '!=', 'text')
            ->where('type', '!=', 'application')
            ->inRandomOrder()
            ->paginate(config('media_per_page'));

        return view('category', compact('category', 'media', 'menu'));
    }
}
