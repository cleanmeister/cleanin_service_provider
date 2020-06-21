<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Auth;
use DB;
use App\Models\Message;
use App\Models\MessageContacts;
use App\Http\Resources\MessageContacts as MessageContactsResource;

class ContactController extends Controller
{

    /*public function index(){
    	$user_id = Auth::user()->id;
    	if(Auth::user()->role_id == 1){
			// $contacts = User::with('profile.messages')
			// ->where('role_id', 2)
			// ->where('id', '<>', $user_id)
			// ->get()->sortByDesc('messages.created_at')
			// ->toArray();

			$contacts = User::with(['profile' => function($query){
				$query->with(['messages' => function ($q) {
					$q->orderBy('created_at','desc')->first();
				
				}]);
			}])
			->where('role_id', 2)
			->where('id', '<>', $user_id)
			->where('approved',1)
			->get()
			->toArray();

    	}
    	if(Auth::user()->role_id == 2){
    		$contacts = User::with('profile')->where('role_id', '<>', 2)->where('id', '<>', $user_id)->get();
    	}

    	if(Auth::user()->role_id == 3){
    		$contacts = User::with('profile')->where('role_id', 2)->orWhere('role_id', 4)->where('id', '<>', $user_id)->get();
    	}
    	if(Auth::user()->role_id == 4){
    		$contacts = User::with('profile')->where('role_id', 2)->orWhere('role_id', 3)->where('id', '<>', $user_id)->get();
    	}
        

        return response()->json($contacts);
    }*/

    public function index(){

        return response()->json($this->CollectContacts('')->original);
    }

    public function CollectContacts($id){

        $user_id = Auth::user()->id;
        //$contacts = DB::SELECT("SELECT * FROM messages Where sender_id = ".$user_id." OR receiver_id = ".$user_id." GROUP BY id;");
        $contacts = MessageContacts::with('user.profile')
            ->whereHas('contacts.profile',function($query) use ($id){
                $query->where('firstname', 'like', '%'.$id.'%')
                ->orWhere('middlename', 'like', '%'.$id.'%')
                ->orWhere('lastname', 'like', '%'.$id.'%');
            })
            ->where('message_contacts.user_id', '=', $user_id)
            ->select()
            ->get();

        $contactUsers = MessageContactsResource::collection($contacts);
        
        return response()->json($contactUsers);
    }

    public function CheckEmail($email){
        $user_id = Auth::user()->id;
        $validator = Validator::make(['email' => $email],[
            'email' => ['required', 'string', 'max:255']
        ]);

        if($validator->passes()){
        	$isEmail = Validator::make(['email' => $email],['email' => ['email']])->passes();
        	if($isEmail){
            	$user = User::with('profile')
	                ->where('id', '!=', $user_id)
	                ->where('email', '=', $email)
	                ->first();

	            if(!$user) return response()->json([]);

	            $userProfile = [
	                'fullname' => $user->profile->firstname.' '.($user->profile->middlename)[0].'. '.$user->profile->lastname,
	                'contact_id' => $user->id,
	                'profile_pic' => $user->profile->profile_pic,
	                'email' => $email
	            ];

	            $message_contacts = MessageContacts::where('user_id', '=', $user_id)->where('contact_id', '=', $userProfile['contact_id'])->first();

	            if($message_contacts) return response()->json(['status'=>1]);

	            return response()->json($userProfile);
            }else{
            	$user = User::with('profile')
	                ->where('id', '!=', $user_id)
	                ->wherehas('profile', function($query) use ($email){
		                $query->where('firstname', 'like', '%'.$email.'%')
		                ->orWhere('middlename', 'like', '%'.$email.'%')
		                ->orWhere('lastname', 'like', '%'.$email.'%');
	                })
	                ->get()->toArray();

	            if(!$user) return response()->json([]);
	            $userProfiles = array();
	            foreach ($user as $key => $value) {
	            	$userProfiles[] = [
		                'fullname' => $value['profile']['firstname'].' '.($value['profile']['middlename'])[0].'. '.$value['profile']['lastname'],
		                'contact_id' => $value['id'],
		                'profile_pic' => $value['profile']['profile_pic'],
		                'email' => $value['email']
		            ];
	            }
	            return response()->json($userProfiles);
            }
        }
        return response()->json($validator->errors(), 422);
    }
}
