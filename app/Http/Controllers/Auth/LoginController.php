<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Hash;
use Illuminate\Auth\Events\PasswordReset;


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

    public function username(){
        return 'username';
    }

    protected function redirectTo()
    {

        if(Auth::user()->role_id==1){
            return '/admin_dashboard';
        }elseif(Auth::user()->role_id==2 ){
            if(Auth::user()->approved == 1){
                return '/ownerhome';
            }
            return '/ownerprofile';
        }elseif(Auth::user()->role_id==3){
            return '/cleanerhome';
        }elseif(Auth::user()->role_id==4){
            return '/clienthome';
        }    
    }

    public function vuelogin(Request $request)
    {
        $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|max:255'
        ]);
        if(Auth::attempt(['username' => $request->username, 'password' => $request->password], $request->remember)){ 
          $user = Auth::user();
          $username = $user->name;
          return response()->json([
            'code' => 200,
            'status'   => 'success',
            'user' => $username,
          ]); 
        } else { 
          return response()->json([
            'errors' => ['username' => '', 'password' => ''],
            'message'   => 'Unauthorized Access',
            'code' => 422,
          ], 422); 
        } 
    }

}
