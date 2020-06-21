<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File; 
use Auth;
use App\User;
use App\Models\UserProfile;
use App\Models\Notification;
use App\Models\CleanerServiceProvider;
use App\CustomHelpers\registrationClass;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index(){
    	$user_id = Auth::user()->id;

    	$profile = UserProfile::with('user')->where('user_id', $user_id)->first();
        $restrictions = "";
        if($profile->user->role_id == 3){
            $restrictions = $profile->user->cleaner_service_provider->cleaner_restrictions;
        }
        $out = array(
            'firstname' => $profile->firstname,
            'middlename' => $profile->middlename,
            'lastname' => $profile->lastname,
            'address' => $profile->address,
            'date_of_birth' => $profile->date_of_birth,
            'profile_pic' => $profile->profile_pic,
            'user_id' => $profile->user_id,
            'mobile_number' => $profile->mobile_number,
            'email' => $profile->user->email,
            'username' => $profile->user->username,
            'type' => $profile->user->role_id,
            'restrictions' => $restrictions
        );

    	return response()->json($out);
    }

    public function store(Request $request){

    }

    public function update(Request $request, $id){
        $user_id = Auth::user()->id;
        //$oldProfilePic = UserProfile::select('profile_pic')->where('user_id', '=', $user_id)->get()->first();
        $oldProfilePic = Auth::user()->profile->profile_pic;
        if($oldProfilePic === null){
            // $request->validate([
            //     'profile_picture' => 'required',
            // ]);
        }elseif($oldProfilePic !== null && $request->profile_pic !== null){
            File::delete(public_path('img'.DIRECTORY_SEPARATOR.'profiles'.DIRECTORY_SEPARATOR.$oldProfilePic));
        }

        $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'middlename' => 'required|max:255',
            'address' => 'required|max:255',
            'date_of_birth' => 'required|max:255|before:today'
        ]);
        
        $profile = Auth::user()->profile;
        $profile->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'address' => $request->address,
            'mobile_number' => $request->mobile_number,
            'date_of_birth' => Carbon::parse($request->date_of_birth)->toDateTimeString(),
        ]);

        if($request->profile_pic !== null){

            $profile_pic = $request->profile_pic;
            $newProfilePic = date("U") . '__' . hash("whirlpool", $request->profile_pic).'.'.registrationClass::extFromBase64($profile_pic);
            registrationClass::MoveImage($profile_pic, $newProfilePic, 'profiles');
            
            $profile->update([
                'profile_pic' => $newProfilePic,
            ]);
        }
        if(Auth::user()->role_id == 2){
            $admins = User::where('role_id', '=', 1)->get();
            foreach ($admins as $key => $value) {
                $notif = Notification::create([
                    'sender_id' => $user_id,
                    'notification' => 'Service Provider Updated their profile.',
                    'receiver_id' => $value->id,
                ]);
            }
        }else if(Auth::user()->role_id == 3){
            $CSP = CleanerServiceProvider::where('cleaner_id', '=', $user_id)->first();
            $CSP->update([
                'cleaner_restrictions' => $request->restrictions
            ]);
            $notif = Notification::create([
                'sender_id' => $user_id,
                'notification' => 'Cleaner Updated his/her profile.',
                'receiver_id' =>  $CSP->service_provider->owner_id,
            ]);
        }

        return response()->json(["msg" => "Update successful!", "code" => 200]);
    }

    public function destroy($id){
        
    }
}
