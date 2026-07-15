<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ConfirmsPasswords;
use Illuminate\Support\Facades\Auth;

class ConfirmPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */

    use ConfirmsPasswords;

    /**
     * Where to redirect users when the intended url fails.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function redirectTo()
    {
        if (Auth::user() && Auth::user()->roles === 'admin') {
            return route('admin.dashboard');
        } elseif (Auth::user() && Auth::user()->roles === 'guru') {
            return route('guru.dashboard');
        } elseif (Auth::user() && Auth::user()->roles === 'orangtua') {
            return route('orangtua.dashboard');
        } elseif (Auth::user() && Auth::user()->roles === 'siswa') {
            return route('siswa.dashboard');
        }

        return route('landing');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
}
