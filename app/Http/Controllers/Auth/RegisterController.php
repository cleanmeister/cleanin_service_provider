<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\UserProfile;
use Carbon\Carbon;
use App\CustomHelpers\registrationClass;
use App\Models\ServiceProvider;
use App\Models\Notification;

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

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
        ]);
    }

    protected function clientregistration(Request $request)
    {   
        $role_id = 4;
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'mobile_number' => ['required', 'string', 'max:255'],
            'date_of_birth' => ['required', 'date'],
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role_id
        ]);

        $user->sendEmailVerificationNotification();
        $profile = $user->profile()->create([
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'lastname' => $request->lastname,
            'address' => $request->address,
            'mobile_number' => $request->mobile_number,
            'date_of_birth' => Carbon::parse($request->date_of_birth)->toDateTimeString()
        ]);
        if($request->profile_picture !== null){

            $profile_pic = $request->profile_picture;
            $newProfilePic = date("U") . '__' . hash("whirlpool", $request->profile_picture).'.'.registrationClass::extFromBase64($profile_pic);
            registrationClass::MoveImage($profile_pic, $newProfilePic, 'profiles');
            
            $profile->update([
                'profile_pic' => $newProfilePic,
            ]);
        }
        $msg = array('code' => 200, 'msg' => 'Registration Successful!');

        return response()->json($msg);
    }

    protected function spregistration(Request $request){
        $role_id = 2;

        $profileValidator = Validator::make($request->userprofile, [
            "username"  => "required|string|unique:users",
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
        ]);

        $tmp = array();
        foreach (json_decode(json_encode($profileValidator->errors()),true) as $key => $value) {
            $tmp[$key] = str_replace($key, "<strong class='text-danger'>".ucwords(str_replace("_", " ", $key))."</strong>", $value[0]) ;
        }

        $servicepValidator = Validator::make($request->servicep, [
            'name' => 'required|string|max:255|unique:service_providers',
            'mobile_number' => 'required|max:255',
            'contact_person' => 'required|string|max:255',
            'company_img' => 'required',
            'business_permit_no' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
        $tmp2 = array();
        foreach (json_decode(json_encode($servicepValidator->errors()),true) as $key => $value) {
            $tmp2[$key] = str_replace($key, "<strong class='text-danger'>".ucwords(str_replace("_", " ", $key) )."</strong>", $value[0]) ;
        }

        if($profileValidator->fails() || $servicepValidator->fails()){
            return response()->json([
                'userprofile' => $tmp,
                'servicep' => $tmp2,
                'message'=>'The given data was invalid.'],
                422
            );
        }

        $user = User::create([
            'username' => $request->userprofile["username"],
            'email' => $request->userprofile["email"],
            'password' => Hash::make($request->userprofile["password"]),
            'role_id' => $role_id
        ]);

        $user->sendEmailVerificationNotification();
        $profile = $user->profile()->create([
            'firstname' => $request->userprofile["firstname"],
            'middlename' => $request->userprofile["middlename"],
            'lastname' => $request->userprofile["lastname"],
            'address' => $request->userprofile["address"],
            'mobile_number' => $request->userprofile["mobile_number"],
            'date_of_birth' => Carbon::parse($request->userprofile["date_of_birth"])->toDateTimeString()
        ]);

        if($request->profile_pic !== null){

            $profile_pic = $request->profile_pic;
            $newProfilePic = date("U") . '__' . hash("whirlpool", $request->profile_pic).'.'.registrationClass::extFromBase64($profile_pic);
            registrationClass::MoveImage($profile_pic, $newProfilePic, 'profiles');
            
            $profile->update([
                'profile_pic' => $newProfilePic,
            ]);
        }

        $newCompanyPic = date("U") . '__' . hash("whirlpool", $request->servicep["company_img"]).'.'.registrationClass::extFromBase64($request->servicep["company_img"]);
        registrationClass::MoveImage($request->servicep["company_img"], $newCompanyPic, 'service_providers\logos');
        
        $newCompanyattach = "";
        if($request->servicep["attachmentimage"] !== null){
            $newCompanyattach = date("U") . '__' . hash("whirlpool", $request->servicep["attachmentimage"]).'.'.registrationClass::extFromBase64($request->servicep["attachmentimage"]);
            registrationClass::MoveImage($request->servicep["attachmentimage"], $newCompanyattach, 'service_providers\permits');
        }

        $sp = ServiceProvider::firstOrCreate(
            ['owner_id' => $user->id ],
            [
                'name' => $request->servicep["name"],
                'company_img' => $newCompanyPic,
                'address' => $request->servicep["address"],
                'mobile_number' => $request->servicep["mobile_number"],
                'contact_person' => $request->servicep["contact_person"],
                'business_permit_no' => $request->servicep["business_permit_no"],
                'permit_img' => $newCompanyattach,
            ]
        );


        $admin_id = User::where('role_id', 1)->first();

        $notif = new Notification();
        $notif->notification = 'Request Business Permit';
        $notif->sender_id = $user->id;
        $notif->receiver_id =$admin_id->id;
        $notif->viewed = 0;
        $notif->save();


        //$msg = array('code' => 200, 'msg' => 'Registration Successful!');
        $msg = array('code' => 200, 'msg' => 'Registration Successful!');

        return response()->json($msg, $msg['code']);
    }
}
