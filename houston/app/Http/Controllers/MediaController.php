<?php

namespace App\Http\Controllers;

use App\Affiliate;
use App\Attachment;
use App\Flag;
use App\Media;
use App\User;
use Cache;
use Carbon\Carbon;
use Conner\Likeable\Like;
use Conner\Likeable\LikeCounter;
use DB;
use Illuminate\Http\Request;
use Event;

class MediaController extends Controller
{
    public function show(Request $request, $slug)
    {
        return $this->showBy($request, 'slug', $slug);
    }

    public function showShortUrl(Request $request, $key)
    {
        return $this->showBy($request, 'key', $key);
    }

    public function showEmbed(Request $request, $key)
    {
        return $this->showBy($request, 'embed', $key);
    }

    public function passwordCheck(Request $request, $slug)
    {
        $media = Media::where('slug', $slug)->with('tagged')->firstOrFail();
        $password = $media->password;

        $passwordCheck = $request->input('media-password');

        if (password_verify($passwordCheck, $password)) {
            $user_id = $media->user_id;
            $user = User::where('id', $user_id)->firstOrFail();
            $username = $user->username;

            $affiliate = Affiliate::where('user_id', $user_id)->first();
            $adblock_ask = (!empty($affiliate->adblock_ask) ? $affiliate->adblock_ask : '0');
            $adblock_off = (!empty($affiliate->adblock_off) ? $affiliate->adblock_off : '0');

            if (\Auth::check()) {
                $owner = ($request->user()->username == $username) ? true : false;
            } else {
                $owner = false;
            }

            // Handle Views Event
            Event::fire('media.view', $media);

            view()->share('website_desc', $media->title);
            view()->share('website_keywords', strtolower($media->tagNames));

            // set the password to an empty string so the password check goes through
            $password = '';

            return view('media.index', compact('media', 'owner', 'adblock_ask', 'adblock_off', 'password'));
        } else {
            flash('The password you entered is incorrect.', 'danger');

            return back();
        }
    }

    public function flag(Request $request)
    {
        (\Auth::check() ? $request->merge(['user_id'=> $request->user()->id]) : $request->merge(['user_id'=> 0]));

        Flag::create($request->all());

        return response()->json(200);
    }

    public function changeGrid($grids)
    {
        if ($grids == 3) {
            session()->put('grids', '<div class="col-xs-12 col-sm-6 col-md-4">');
        } else {
            session()->put('grids', '<div class="col-xs-12 col-sm-6">');
        }

        return back();
    }

    // will process the routes to show media requested by full URL/slug and by key
    public function showBy(Request $request, $type, $key)
    {
        $media_key = 'media_'.$type.'_'.$key;

        if (Cache::has($media_key)) {
            $media = Cache::get($media_key);
        } else {
            if ($type == 'embed') {
                $media = Media::where('key', $key)->with('tagged', 'post', 'post.comments')->firstOrFail();
            } else {
                $media = Media::where($type, $key)->with('tagged', 'post', 'post.comments')->firstOrFail();
            }

            Cache::put($media_key, $media, config('expires_at_interval'));
        }

        $team = \App\Team::find($media->post->team_id);

        $user_id = $media->user_id;
        $anonymous = $media->anonymous;
        $password = $media->password;

        $user = User::where('id', $user_id)->first();
        if (empty ($user)) {
            $user = User::find(1);
        }
        $username = $user->username;

        $affiliate = Affiliate::where('user_id', $user_id)->first();
        $adblock_ask = (!empty($affiliate->adblock_ask) ? $affiliate->adblock_ask : '0');
        $adblock_off = (!empty($affiliate->adblock_off) ? $affiliate->adblock_off : '0');

        if (\Auth::check()) {
            $owner = ($request->user()->username == $username) ? true : false;
            $admin = ($request->user()->type == 'admin') ? true : false;
        } else {
            $owner = false;
            $admin = false;
        }

        // Handle Views Event
        Event::fire('media.view', $media);

        view()->share('website_desc', $media->title);
        view()->share('website_keywords', strtolower($media->tagNames));

        if ($type == 'embed') {
            return view('media.embed_media', compact('team', 'media', 'owner', 'adblock_ask', 'adblock_off', 'password', 'anonymous', 'admin', 'user'));
        } else {
            return view('media.index', compact('team', 'media', 'owner', 'adblock_ask', 'adblock_off', 'password', 'anonymous', 'admin', 'user'));
        }
    }

    public function addMediaPlay($key)
    {
        $media = Media::where('key', $key)->firstOrFail();

        $response = Event::fire('media.play', $media);

        return $response;
    }

    public function getOpenloadDownloadURL($key)
    {
//        $media = Media::where('key', $key)->first();
//        $user_agent = request()->header('User-Agent');
//
//        $cmd = 'youtube-dl -e -g  "' . $media->cloned . '" --user-agent "' . $user_agent . '"';
//        //dd($cmd);
//        $out = shell_exec($cmd);
//
//        $info_arr = explode(PHP_EOL, $out);
//        $stream_url = $info_arr[1];

        return 'test return';
    }
}
