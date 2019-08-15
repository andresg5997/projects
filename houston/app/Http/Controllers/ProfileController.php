<?php

namespace App\Http\Controllers;

use App\Followers;
use App\Media;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request, $username)
    {
        $page = 'index';

        $user = User::where('username', $username)->firstOrFail();

        if (Auth::check()) {
            $owner = ($request->user()->username == $username) ? true : false;
            $admin = ($request->user()->type == 'admin') ? true : false;
        } else {
            $owner = false;
            $admin = false;
        }

        if ($owner || $admin) {
            $data = Media::where('user_id', $user->id)->latest()->paginate(config('media_per_page'));
        } else {
            $data = Media::where('user_id', $user->id)->where('private', 0)->where('anonymous', 0)->latest()->paginate(config('media_per_page'));
        }

        return view('profile', compact('user', 'page', 'data', 'users', 'owner'));
    }

    public function likes(Request $request, $username)
    {
        $page = 'likes';

        $user = User::where('username', $username)->firstOrFail();

        $data = Media::whereLikedBy($user->id)->paginate(config('media_per_page'));

        if (Auth::check()) {
            $owner = ($request->user()->username == $username) ? true : false;
        } else {
            $owner = false;
        }

        return view('profile', compact('user', 'page', 'data', 'users', 'owner'));
    }

    public function followers(Request $request, $username)
    {
        $page = 'followers';

        $user = User::where('username', $username)->firstOrFail();

        $users = User::whereIn('id', Followers::where('follower_id', $user->id)->pluck('user_id')->all())
           ->paginate(config('media_per_page'));

        if (Auth::check()) {
            $owner = ($request->user()->username == $username) ? true : false;
        } else {
            $owner = false;
        }

        return view('profile', compact('user', 'page', 'users', 'owner'));
    }

    public function following(Request $request, $username)
    {
        $page = 'following';

        $user = User::where('username', $username)->firstOrFail();

        $users = User::whereIn('id', Followers::where('user_id', $user->id)->pluck('follower_id')->all())
           ->paginate(config('media_per_page'));

        if (Auth::check()) {
            $owner = ($request->user()->username == $username) ? true : false;
        } else {
            $owner = false;
        }

        return view('profile', compact('user', 'page', 'users', 'owner'));
    }
}
