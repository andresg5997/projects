<?php

namespace App;

use Laravel\Socialite\Contracts\Provider;

class SocialAccountService
{
    public function createOrGetUser(Provider $provider)
    {
        $providerUser = $provider->user();
        $providerName = class_basename($provider);

        $account = SocialAccount::whereProvider($providerName)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider'         => $providerName,
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                if ($providerUser->getEmail()) {
                    $email = $providerUser->getEmail();
                } else {
                    $email = time().'@clooud.tv';
                }

                $user = User::create([
                    'email'        => $email,
                    'username'     => make_slug($providerUser->getName()).rand(000, 999),
                    'type'         => 'user',
                    'password'     => 'SocialUsers'.date('YYmmddHHiiss').rand(6, 11),
                    'affiliate_id' => str_random(10),
                    'verified'     => 1,
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }
    }
}
