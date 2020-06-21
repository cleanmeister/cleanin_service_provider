<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use DB;
use App\Http\Resources\User as UserResource;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
use App\Models\ServiceProvider;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   
    public function index(){

        $word = request()->word;
        // $users =  User::join('user_profiles','users.id','=','user_profile.user_id')->get();
        $data = User::whereHas('profile', function($query) use ($word){
                $query->where('firstname', 'like', '%'.$word.'%')
                ->orWhere('middlename', 'like', '%'.$word.'%')
                ->orWhere('lastname', 'like', '%'.$word.'%');})
            ->where('id', '<>', Auth::user()->id)->where('id', '<>', 1)
            ->whereNotIn('role_id',[3,4])->get();
        $users = UserResource::collection($data);
        $out = array();
        /**/foreach ($users as $key => $value) {
            $tmp = ServiceProvider::where('owner_id', '=', $value['id'])->first();
            //$tmp = json_decode(json_encode($tmp));
            if($tmp != null && $tmp->approved == 1){
                $users[$key]['Service_Provider'] = $tmp;
                $out[] = $users[$key] ;
            }
        }
        //$ServiceProvider = ServiceProvider::where('owner_id',)

    	return response()->json($out);

    }

    public function getRoles(){
        $roles = Role::all()->toArray();
        array_push($roles, array('id'=>7, 'name' => 'Transactions'));
        return response()->json($roles);
    }

    public function store(){

    }

    public function update(Request $request){

    }

    public function destroy(){

    }

    public function activate($id){
    	$user = User::findOrFail($id);
    	$user->update([
    		'is_active' => 1
    	]);

        return response()->json(["msg" => "Activation successful!", "code" => 200]);
    }

    public function deactivate($id){

        $user = User::findOrFail($id);
        if($user->role_id !== 2){
            return response()->json(["msg" => "Cannot Deactivate. User is not a Service Provider.", "code" => 422], 422);
        }
        $ClientSchedules = ServiceProvider::where('owner_id', $id)
            ->leftjoin('services', 'services.service_provider_id', '=', 'service_providers.id')
            ->leftjoin('schedules', 'schedules.service_id', '=', 'services.id')
            ->leftjoin('client_schedules', 'client_schedules.schedule_id', '=', 'schedules.id')
            ->wherein('client_schedules.status', [1,2])
            ->select('client_schedules.id')->get();

        if($ClientSchedules->count() > 0){
            return response()->json(["title" => "Cannot deactivate account.", "message" => "There are ongoing transactions, please contact the service provider.", "code" => 422], 422);
        }
        
        $user->update([
            'is_active' => 0
        ]);
        
        return response()->json(["msg" => "Deactivate successful!", "code" => 200]);

    }

    public function blocked_client($id){

    }

    public function change_password(Request $request){
        /**/$validator = Validator::make($request->all(), [
            'old_password' => [
                'required', function ($attribute, $value, $fail) {
                    if (!Hash::check($value, Auth::user()->password)) {
                        $fail('<h5><b class="text text-danger">Old Password</b> do not match your current password.</h5>');
                    }
                }
            ],
            'new_password' => [
                'required', function ($attribute, $value, $fail){
                    if (Hash::check($value, Auth::user()->password)) {
                        $fail('<h5><b class="text text-danger">New password</b> can\'t be the same with your current password.</h5>');
                    }
                }
            ]
        ]);
        $this->validate($request, [
            'old_password' => 'required|min:8',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|min:8|same:new_password'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => "The given data was invalid.", 'errors' => $validator->messages()], 422);
        }

        $user_id = Auth::user()->id;
        $user = User::findOrFail($user_id);
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);
        if(Auth::user()->role_id == 2){
            $admins = User::where('role_id', '=', 1)->get();
            foreach ($admins as $key => $value) {
                $notif = Notification::create([
                    'sender_id' => $user_id,
                    'notification' => 'Service Provider Updated their password.',
                    'receiver_id' => $value->id,
                ]);
            }
        } else if(Auth::user()->role_id == 2){
            $notif = Notification::create([
                'sender_id' => $user_id,
                'notification' => 'Cleaner Updated his/her password.',
                'receiver_id' => $value->id,
            ]);
        }
        Auth::logout();

        return response()->json(["msg" => "Update successful!", "code" => 200]);
    }

    public function update_account(Request $request){
        $user_id = Auth::user()->id;
        $this->validate($request, [
            'username' => 'required|max:255',
            'email' => 'required|email|max:255'
        ]);

        $error = array();

        $email = User::where('email', '=', $request->email)
            ->where('id', '!=', $user_id)
            ->get();
        $user = User::where('username', '=', $request->username)
            ->where('id', '!=', $user_id)
            ->get();

        if($email->count() > 0){
            $error['email'] = array('The email has already been taken.');
        }
        if($user->count() > 0){
            $error['username'] = array('The username has already been taken.');
        }

        if(count($error) > 0){
            return response()->json(["message" => "The given data was invalid.","errors" => $error], 422);
        }

        Auth::user()->update([
            'username' => $request->username,
            'email' => $request->email
        ]);
        if(Auth::user()->role_id == 2){
            $admins = User::where('role_id', '=', 1)->get();
            foreach ($admins as $key => $value) {
                $notif = Notification::create([
                    'sender_id' => $user_id,
                    'notification' => 'Service Provider Updated account.',
                    'receiver_id' => $value->id,
                ]);
            }
        }
        //Auth::logout();

        //return response()->json(["message" => "Please wait while we redirect you to the login page", "msg" => "Update successful!", "code" => 200]);
        return response()->json(["message" => "", "msg" => "Update successful!", "code" => 200]);
    }

}
