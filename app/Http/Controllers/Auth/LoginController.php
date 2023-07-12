<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\LogController;
use App\Models\Log;
use Illuminate\Support\Facades\DB;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $this->validate($request, [
            'username' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt(array('username' => $input['username'], 'password' => $input['password']))) {

            $logQuery = new LogController; // store the LogController

            list($user_id, $user_type) = $logQuery->startLog(); // store the value from the returned parameter of startLog function

            $activity = $user_type . " logged in"; // store the activity where user is logged on

            $logQuery->endLog($user_id, $user_type, $activity); // pass the value to the endLog parameter that will create a log

            if ($user_type == 'admin') {
                return redirect()->route('dashboard.admin');
            } else if ($user_type == 'cashier') {
                return redirect()->route('dashboard.cashier');
            }
        } else {
            return redirect()->route('login')
                ->withErrors([
                    'username' => 'Username or password is incorrect.',
                ]);
        }
    }

    public function logout(Request $request)
    {
        $logQuery = new LogController; // store the LogController

        list($user_id, $user_type) = $logQuery->startLog(); // store the value from the returned parameter of startLog function

        $activity = $user_type . " logged out"; // store the activity where user is logged out

        $logQuery->endLog($user_id, $user_type, $activity); // pass the value to the endLog parameter that will create a log


        Auth::logout(); //logout the authenticated user

        $request->session()->invalidate(); //remove all the data stored in the session

        $request->session()->regenerateToken(); //regenerate token for the next session


        return redirect('/login'); //redirect the user to login
    }
}
