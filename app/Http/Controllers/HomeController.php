<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(){
        if(Auth::user()->role_id == 1)
        {
            return view('pages.admin_dashboard');
        }
        if(Auth::user()->role_id == 2)
        {
            if(Auth::user()->approved == 0){
                $approved = array("DeclinedRequest" => (ServiceProvider::where('owner_id', Auth::user()->id)->first() == null));
                return view('pages.service_provider_profile')->with($approved);
            }
            return view('ownerhome');
        }
        if(Auth::user()->role_id == 3){
            return view('cleanerhome');
        }
        if(Auth::user()->role_id == 4){
            return view('clienthome');
        }
    }

    public function admin(){
        return view('pages.admin_dashboard');
    }

    public function owner(){
        if(Auth::user()->approved == 0){
            $approved = array("DeclinedRequest" => (ServiceProvider::where('owner_id', Auth::user()->id)->first() == null));
            return view('pages.service_provider_profile')->with($approved);
        }
        return view('ownerhome');
    }

    public function ownerprofile(){
        return view('pages.service_provider_profile');
    }

    public function cleaner(){
        return view('cleanerhome');
    }

    public function client(){
        return view('clienthome');
    }
}
