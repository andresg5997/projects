<?php

namespace App\Http\Controllers\User;

use App\Followers;
use App\Http\Controllers\Controller;
use App\Media;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FollowController extends Controller
{
    public function __construct()
    {
        view()->share('website_desc', config('website_desc'));
        view()->share('website_keywords', config('website_keywords'));
    }

    //
    public function putFollow(Request $request)
    {
        if ($request->user()->id == $request->input('id')) {
            return false;
        }

        $follow_exist = Followers::where('user_id', $request->user()->id)->where('follower_id', $request->input('id'));

        if ($follow_exist->count()) {
            $follow_exist->delete();
        } else {
            $user = User::where('id', $request->input('id'))->first();

            if ($user->notification_following == 1) {
                $user_email = $user->email;

                $follower = User::where('id', $request->user()->id)->first();

                $data = ['user' => $user, 'follower' => $follower];
                Mail::send('emails.new_follower', $data, function ($message) use ($user_email) {
                    $message->from('noreply@clooud.tv', 'Clooud Media')
                        ->to($user_email)
                        ->subject('New Follower!');
                });
            }

            Followers::create([
                'user_id'     => $request->user()->id,
                'follower_id' => $request->input('id'),
            ]);
        }

        $followers_count = Followers::where('follower_id', $request->input('id'))->count();

        return response()->json($followers_count);
    }

    public function followFeeds(Request $request)
    {
        $menu = 'followFeeds';

        $media = Media::whereIn('user_id', Followers::where('user_id', $request->user()->id)
            ->pluck('follower_id')
            ->all())->where('private', 0)->latest()->paginate(config('media_per_page'));

        return view('home', compact('menu', 'media'));
    }
}
