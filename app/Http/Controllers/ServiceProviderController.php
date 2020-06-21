<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\ServiceProvider;
use App\Models\Notification;
use App\User;
use App\CustomHelpers\registrationClass;
use Illuminate\Support\Facades\Validator;


class ServiceProviderController extends Controller
{
    public function index(){

    }

    public function store(Request $request){

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

        if($servicepValidator->fails()){
            return response()->json([
                'servicep' => $tmp2,
                'message'=>'The given data was invalid.'],
                422
            );
        }

        $newCompanyPic = date("U") . '__' . hash("whirlpool", $request->servicep["company_img"]).'.'.registrationClass::extFromBase64($request->servicep["company_img"]);
        registrationClass::MoveImage($request->servicep["company_img"], $newCompanyPic, 'service_providers\logos');
        
        $newCompanyattach = "";
        if($request->servicep["attachmentimage"] !== null){
            $newCompanyattach = date("U") . '__' . hash("whirlpool", $request->servicep["attachmentimage"]).'.'.registrationClass::extFromBase64($request->servicep["attachmentimage"]);
            registrationClass::MoveImage($request->servicep["attachmentimage"], $newCompanyattach, 'service_providers\permits');
        }

        $sp = ServiceProvider::firstOrCreate(
            ['owner_id' => Auth::user()->id ],
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
        $notif->notification = 'Request Business Permit<br/>'.$request->servicep["name"];
        $notif->sender_id = Auth::user()->id;
        $notif->receiver_id =$admin_id->id;
        $notif->viewed = 0;
        $notif->save();
        $msg = array('code' => 200, 'msg' => 'Registration Successful!');

        return response()->json($msg, $msg['code']);
    }

    /*public function store(Request $request){
        return response()->json($request);

        $request->validate([
            'name' => 'required|unique:service_providers|max:255',
        ]);

    	$owner_id = Auth::user()->id;

    	$image_name_1 = $request->name.".jpg";
		$image_name_2 = $request->business_permit_no.".jpg";

		$request->company_img->move(public_path('img/service_providers/logos'), $image_name_1);
		
        $filename = "";
		if(!empty($request->requestor_attachments)){
            $uploadedFile = $request->file('requestor_attachments');
            $filename = time(). '_' .$uploadedFile->getClientOriginalName();
			$uploadedFile->move(public_path('img/service_providers/permits'), $filename);
		}
    	$sp = ServiceProvider::firstOrCreate(
            ['owner_id' => $owner_id ],
            [
        		'name' => $request->name,
        		'company_img' => $image_name_1,
        		'address' => $request->company_address,
        		'mobile_number' => $request->mobile_number,
        		'contact_person' => $request->contact_person,
        		'business_permit_no' => $request->business_permit_no,
        		'permit_img' => $filename,
        	]
        );

        $admin_id = User::where('role_id', 1)->first();

		$notif = new Notification();
		$notif->notification = 'Request Business Permit';
		$notif->sender_id = $owner_id;
		$notif->receiver_id =$admin_id->id;
		$notif->viewed = 0;
		$notif->save();

    	return response()->json(["status" => 'success',"message" => 'Request Successfully Sent! Please wait for the Admin to process your Request']);
    }*/

    public function update(Request $request){
        return response()->json($request);
    }

    public function activate($id){
    	$sp = ServiceProvider::findOrFail($id);
    	$sp->update([ 'approved' => 1, ]);

    	$sp->owner()->update([ 'approved' => 1 ]);

    	return response()->json();
    }

    public function deactivate($id){
    	$sp = ServiceProvider::findOrFail($id);
    	$sp->update([
    		'approved' => 2,
    	]);

    	$sp->owner()->update([
    		'approved' => 2,
    	]);

    	return response()->json();
    }

    public function destrot($id){
    	$sp = ServicerPorvider::findOrFail($id);


    	return response()->json();
    }
}
