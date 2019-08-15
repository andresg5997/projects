<?php

namespace App\Http\Controllers;

use App\Media;
use Cache;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('landing');
        if(!\Auth::check()){
            return redirect()->route('login');
        }else{
            return redirect()->route('teams.index');
        }

        $menu = 'recent';
        $minutes = 0.05;

        $media = Cache::remember('media', $minutes, function () {
            $media_query = (new Media())
                ->newQuery()
                ->where('private', 0)
                ->where('approved', 1)
                ->where('type', '!=', 'text')
                ->where('type', '!=', 'application')
                ->latest()
                ->paginate(config('media_per_page'));

            return $media_query;
        });

        return view('home', compact('media', 'menu'));
    }

    public function clearSession(Request $request)
    {
        $request->session()->forget('retrieve');
    }

    public function aboutUs()
    {
        return view('aboutus');
    }

    public function faq()
    {
        return view('faq');
    }

    public function supportedMediaHosts()
    {
        return view('supported-media-hosts');
    }

    public function tos()
    {
        return view('tos');
    }

    public function news()
    {
        return view('news');
    }

    public function privacy()
    {
        return view('privacy');
    }

    public function copyright()
    {
        return view('copyright');
    }

    public function subscriptions()
    {
        return view('subscriptions');
    }

    public function dmca()
    {
        return view('dmca');
    }

    public function affiliate()
    {
        return view('affiliate');
    }

    public function popular()
    {
        $menu = 'popular';

        $media = Media::orderByMostPopular()
            ->where('private', 0)
            ->where('approved', 1)
            ->paginate(config('media_per_page'));

        return view('home', compact('media', 'menu'));
    }

    public function likes()
    {
        $menu = 'likes';

        $media = Media::orderByMostLikes()
            ->where('private', 0)
            ->where('approved', 1)
            ->where('type', '!=', 'text')
            ->where('type', '!=', 'application')
            ->paginate(config('media_per_page'));

        return view('home', compact('media', 'menu'));
    }

    public function comments()
    {
        $menu = 'comments';

        $media = Media::orderByMostComments()
            ->where('private', 0)
            ->where('approved', 1)
            ->where('type', '!=', 'text')
            ->where('type', '!=', 'application')
            ->paginate(config('media_per_page'));

        return view('home', compact('media', 'menu'));
    }

    public function random()
    {
        $menu = 'random';

        $media = Media::inRandomOrder()
            ->where('private', 0)
            ->where('approved', 1)
            ->where('type', '!=', 'text')
            ->where('type', '!=', 'application')
            ->paginate(config('media_per_page'));

        return view('home', compact('media', 'menu'));
    }

    public function search(Request $request)
    {
        $menu = 'search';

        $q = $request->get('q');

        if ($q != '') {
            $media = Media::where('title', 'LIKE', '%'.$q.'%')
                ->orWhere('body', 'LIKE', '%'.$q.'%')
                ->where('private', 0)
                ->where('approved', 1)
                ->paginate();

            return view('home', compact('menu', 'q', 'media'));
        }

        return back();
    }

    public function clooudFAQShow()
    {
        return view('clooud-faq');
    }
}
