<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function logout(){
        if(\Auth::check()){
            \Auth::logout();
            return redirect()->route('login');
        }
        return redirect()->route('login');
    }

    public function confirmEmail($token)
    {
        $user = User::where('token', $token)->first();
        if($user) {
            $user->confirmed = '1';
            $user->save();
            flash('Your email was successfully confirmed!', 'success');
            return redirect()->route('teams.index');
        }
        flash('Your email is already confirmed.');
        return redirect()->route('teams.index');
    }
}
