<?php

namespace App\Http\Controllers\User;

use App\Affiliate;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\SettingRequest;
use File;
use Storage;
use Symfony\Component\HttpFoundation\Request;

class SettingController extends Controller
{
    //
    public function profile(Request $request)
    {
        $page = 'profile';

        $user = $request->user();

        return view('user.settings', compact('page', 'user'));
    }

    public function updateProfile(SettingRequest $request)
    {
        $request->user()->update([
            'username' => $request->input('username'),
            'email'    => $request->input('email'),
        ]);

        flash('Your account settings have been updated!', 'success');

        return back();
    }

    public function updateAvatar(SettingRequest $request)
    {
        // $file = $request->user()->id.'.'.$request->file('avatar')->getClientOriginalExtension();
        $file = $request->user()->id.'.jpg';
        if(\App::environment('production')){    
            Storage::disk('s3')->put($file, File::get($request->file('avatar')));
        }else{
            Storage::disk('avatars')->put($file, File::get($request->file('avatar')));
        }

        flash('Your Account Avatar has been updated!', 'success');

        return back();
    }

    public function editPassword(Request $request)
    {
        $page = 'password';

        $user = $request->user();

        return view('user.settings', compact('page', 'user'));
    }

    public function updatePassword(SettingRequest $request)
    {
        $request->user()->update([

            'password' => bcrypt($request->input('password')),
        ]);

        flash('Your password has been updated!', 'success');

        return back();
    }

    public function editAffiliate(Request $request)
    {
        $page = 'affiliate';

        $user = $request->user();

        $status = Affiliate::where('user_id', $user->id)->where('status', 1)->first();

        return view('user.settings', compact('page', 'user', 'status'));
    }

    public function updateAffiliate(SettingRequest $request)
    {
        if ($request->input('affiliate') !== null) {
            $status = 1;
        } else {
            $status = 0;
        }

        Affiliate::updateOrCreate(
            ['user_id' => $request->user()->id],
            ['status' => $status, 'ip' => $request->ip()]
        );

        flash('Your affiliate settings have been updated!', 'success');

        return back();
    }

    public function editNotifications(Request $request)
    {
        $page = 'notifications';

        $user = $request->user();

        return view('user.settings', compact('page', 'user'));
    }

    public function updateNotifications(SettingRequest $request)
    {
        if ($request->input('followers') !== null) {
            $followers = 1;
        } else {
            $followers = 0;
        }

        if ($request->input('following') !== null) {
            $following = 1;
        } else {
            $following = 0;
        }

        if ($request->input('comments') !== null) {
            $comments = 1;
        } else {
            $comments = 0;
        }

        $request->user()->update([
            'notification_followers_add_media' => $followers,
            'notification_following'           => $following,
            'notification_comments'            => $comments,
        ]);

        flash('Your notification settings have been updated!', 'success');

        return back();
    }
}
