<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Page;
use Cookie;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Jrean\UserVerification\Facades\UserVerification;
use Jrean\UserVerification\Traits\VerifiesUsers;
use Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;
    use VerifiesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/teams';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $tos_parent_id = Page::where('slug', 'tos')->first()->parent;
        $tos_parent_slug = Page::where('id', $tos_parent_id)->first()->slug;
        view()->share('tos_parent_slug', $tos_parent_slug);

        $this->middleware('guest', ['except' => ['getVerification', 'getVerificationError']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        if (config('captcha_active')) {
            return Validator::make($data, [
                'username'             => 'required|max:50|alpha_num|unique:users',
                'email'                => 'required|email|max:255|unique:users',
                'password'             => 'required|min:6|confirmed',
                'g-recaptcha-response' => 'required|captcha',
            ]);
        } else {
            return Validator::make($data, [
                'username'             => 'required|max:50|alpha_num|unique:users',
                'email'                => 'required|email|max:255|unique:users',
                'password'             => 'required|min:6|confirmed',
            ]);
        }
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return User
     */
    protected function create(array $data)
    {
        $referred_by = Cookie::get('referral');

        return User::create([
            'type'         => 'user',
            'username'     => $data['username'],
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'password'     => bcrypt($data['password']),
            'affiliate_id' => str_random(10),
            'referred_by'  => $referred_by,
            'confirmed'    => '0',
            'token'        => str_random(20)
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());

        event(new Registered($user));

        $this->guard()->login($user);

        if (config('sparkpost_secret')) {
            UserVerification::generate($user);
            // UserVerification::send($user, 'Verify Your E-Mail Address!');

            session(['flash_notification.message' => 'Thank you for signing up! Please verify your email address now.']);
        } else {
            session(['flash_notification.message' => 'Thank you for signing up! Enjoy your stay.']);
        }
        session(['flash_notification.level' => 'info']);
        session(['flash_notification.important' => 1]);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }
}
