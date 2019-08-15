<?php

namespace App\Http\Controllers;

use App\SocialAccountService;
use Socialite;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(SocialAccountService $service, $provider)
    {
        // when facebook call us a with token
        //$providerUser = \Socialite::driver('facebook')->user();

        //$user = $service->createOrGetUser(Socialite::driver('facebook')->user());

        $user = $service->createOrGetUser(Socialite::driver($provider));

        auth()->login($user);

        return redirect()->to('/');
    }
}
