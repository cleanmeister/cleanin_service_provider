<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Notification;

class PageController extends Controller
{

    public function service_providers(){
        return view('pages.service_providers');
    }

    public function cleaners(){
        return view('pages.cleaners');
    }

    public function schedules(){
        return view('pages.schedules');
    }

    public function clients(){
        return view('pages.clients');
    }

    public function messages(){
        return view('pages.messages');
    }

    public function messaging(){
        return view('pages.messaging')->with('email', request()->get('email'));
    }

    public function notifications(){
        $receiver_id = Auth::user()->id;
        $notifications = Notification::where('receiver_id', $receiver_id)->where('viewed', 0)->update([
            'viewed' => 1
        ]);
        
        return view('pages.notifications');
    }

    public function services(){
        return view('pages.services');
    }

    public function service(){
        return view('pages.service');
    }

    public function pending_requests(){
        return view('pages.pending_requests');
    }

    public function manage_accounts(){
        return view('pages.manage_accounts');
    }

    public function service_provider_services(){
        return view('pages.service_provider_services');
    }

    public function client_service_provider(){
        return view('pages.client_service_provider');
    }

    public function client_booking(){
        return view('pages.client_booking');
    }

    public function cleaner_booking(){
        return view('pages.cleaner_booking');
    }

    public function service_provider_booking(){
        return view('pages.service_provider_booking');
    }

    public function sp_booking(){
        return view('pages.sp_booking');
    }

    public function profile(){
        return view('pages.profile');
    }

    public function change_password(){
        return view('pages.change_password');
    }

    public function client_report(){
        return view('pages.client_report');
    }

    public function cleaner_report(){
        return view('pages.cleaner_report');
    }

    public function service_provider_report(){
        return view('pages.service_provider_report');
    }

    public function admin_report(){
        return view('pages.admin_report');
    }
    public function complaints(){
        return view('pages.complaints');
    }
    public function inactive(){
        return view('pages.inactive');
    }
    public function spregistration(){
        return view('auth.register')->with('serviceProvider', true);
    }

    public function landingpage(){
        return view('pages.landing');
    }
}
