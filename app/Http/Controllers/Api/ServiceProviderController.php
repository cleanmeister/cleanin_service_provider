<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ServiceProvider;
use App\Models\Service;
use App\Models\Schedule;
use App\Models\CleanerSchedule;
use App\Models\ClientSchedule;
use App\Models\BlockedUser;
use App\Models\CleanerServiceProvider;
use App\Models\Notification;
use DB;
use AUTH;
use App\User;
use App\Http\Resources\ServiceProvider as ServiceProviderResource;
use App\Http\Resources\CleanerServiceProvider as CleanerServiceProviderResource;
use App\Http\Resources\CleanerSchedule as CleanerScheduleResource;
use App\Http\Resources\Service as ServiceResource;
use Illuminate\Support\Facades\File; 

class ServiceProviderController extends Controller
{

    public function index()
    {
        return response()->json($this->searchProvider('')->original);
    }
    
    public function countServiceProviders(){
        $data = ServiceProvider::where('approved', 1)->count();
        
        return response()->json($data);
    }

    public function store(Request $request)
    {

    }

    public function show($id){
        //if(!ctype_digit($id)){ return response()->json(['undefined!']); }
        $data = ServiceProvider::findOrFail($id);
        //$service_provider = new ServiceProviderResource($data);
        
        $service_provider = json_decode(json_encode($data), true);
        $out = array();
        $owner = User::findOrFail($service_provider['owner_id']);

        $out['id'] = $service_provider['id'];
        $out['name'] = $service_provider['name'];
        $out['address'] = $service_provider['address'];
        $out['owner'] = $owner->profile->firstname.' '.$owner->profile->middlename.' '.$owner->profile->lastname;
        $out['business_permit_no'] = $service_provider['business_permit_no'];
        $out['contact_no'] = $service_provider['mobile_number'];
        $out['contact_person'] = $service_provider['contact_person'];
        $out['permit_img'] = $service_provider['permit_img'];
        $out['company_img'] = $service_provider['company_img'];

        $services = Service::where('service_provider_id', $service_provider['id'])
            ->wherehas('schedules', function($query){
                $query->where('available', '=', 1);
            })
            ->where('status', '=', 1)
            ->get();

        $out['services'] = ServiceResource::collection($services);
        //$out['services'] = 
        $out['cleaners'] = CleanerServiceProviderResource::collection($data->SPCleaners);
        // foreach ($services_poffered as $key => $value) {
            //$out['services'][] = $key;
        // }
        return response()->json($out);
    }

    public function searchProvider($search){
        //if(!ctype_digit($id)){ return response()->json(['undefined!']); }

        $id = Auth::user()->id;
        $data = ServiceProvider::with('services')->has('services')
            ->where('service_providers.name', 'LIKE', '%'.$search.'%')->get();

        $service_provider = ServiceProviderResource::collection($data);
        $service_providers = json_decode(json_encode($service_provider));

        $out = array();
        foreach ($service_providers as $key => $value) {
            $blocked = BlockedUser::where('blocker_id', $value->id)->where('blocked_id', $id)->first();
            if(count($value->cleaners) > 0 && $blocked == null && $value->is_active == 1)
            {   
                $int = 0; $tmp = array();
                $cleaners = array();
                foreach ($value->cleaners as $keys => $values) {
                    $tmp['id'] = DB::SELECT("SELECT Count(id) AS ctr FROM schedules WHERE cleaner_id = ".$values->id." AND available = 1;");
                    $tmpID = $values->id;
                    $cleaners = CleanerServiceProvider::wherehas('cleaner', function($query){
                        $query->where('is_active', '=', 1);
                    })
                    ->where('cleaner_service_provider.cleaner_id', '=', $values->id)
                    ->where('cleaner_service_provider.status', '=', 1)
                    ->get();

                    if($cleaners->count() > 0 && $tmp['id'][0]->ctr > 0) $int++;
                }
                $services = null;
                foreach ($value->services as $keys => $values) {
                    $service_id = $values->id;
                    $servicestmp = Service::with('schedules')->wherehas('schedules', function($query){
                        $query->where('schedules.available', '=', 1)->groupBy('schedules.id', 'ASC');
                    })
                    ->where('id', '=', $service_id)
                    ->where('status', '=', 1)->get();
                    if($servicestmp->count() > 0)$services[] = $servicestmp;
                }
                $temporary = array();
                $temporary['id'] = $value->id;
                $temporary['company_img'] = $value->company_img;
                $temporary['name'] = $value->name;
                $temporary['ratings'] = $this->computeRating($value->id)->original;
                if($services != null && count($services[0]) > 0){
                    $temporary['services'] = $services;
                }

                //$value->has('services');
                if($int>0){
                    //$out[] = json_decode(json_encode($value));
                    //$value['services']
                    $out[] = $temporary;
                    //$out[] = $tmp;
                }
            }
        }

        return response()->json($out);
    }

    public function decline($id){
        $SP = ServiceProvider::where('id',$id)->first();

        if(!$SP) return respones()->json(array("message" => "The id provided is not valid!"), 422);
        $company_img = public_path().'\\img\\service_providers\\logos\\'.$SP->company_img;
        File::delete($company_img);
        if($SP->permit_img != '' || $SP->permit_img != null){
            $permit_img = public_path().'\\img\\service_providers\\permits\\'.$SP->permit_img;
            File::delete($permit_img);
        }

        $SP->delete();
        $msg = array("msg" => "Success!", "code" => 200);
        return response()->json($msg);
    }

    public function approved($id)
    {
        $service_provider = ServiceProvider::findOrFail($id);
        $service_provider->update([
            'approved' => 1
        ]);
        $service_provider->owner()->update([
            'approved' => 1
        ]);

        $owner_id = $service_provider->owner_id;
        $use = User::where('id', $owner_id)->update([
            'approved' => 1
        ]);

        return response()->json();
    }

    public function get_sp(){
        $user_id = Auth::user()->id;
        $service_provider = ServiceProvider::where('owner_id', '=', $user_id)->get()->first();
        $out = array();

        $out['id'] = $service_provider['id'];
        $out['name'] = $service_provider['name'];
        $out['address'] = $service_provider['address'];
        $out['business_permit_no'] = $service_provider['business_permit_no'];
        $out['contact_no'] = $service_provider['mobile_number'];
        $out['contact_person'] = $service_provider['contact_person'];
        $out['permit_img'] = $service_provider['permit_img'];
        $out['company_img'] = $service_provider['company_img'];
        return response()->json($out);

    }

    public function update_sp(Request $request){
        $request->validate([
            //'name' => 'required|max:255',
            'address' => 'required|max:255',
            'contact_no' => 'required|max:255',
            'contact_person' => 'required|max:255',
        ]);
        $user_id = Auth::user()->id;
        $service_provider = ServiceProvider::where('owner_id', '=', $user_id)->get()->first();

        $service_provider->update([
            //'name' => $request->name,
            'address' => $request->address,
            'contact_no' => $request->contact_no,
            'contact_person' => $request->contact_person,
        ]);


        if(Auth::user()->role_id == 2){
            $admins = User::where('role_id', '=', 1)->get();
            foreach ($admins as $key => $value) {
                $notif = Notification::create([
                    'sender_id' => $user_id,
                    'notification' => 'Service Provider Updated business profile.',
                    'receiver_id' => $value->id,
                ]);
            }
        }

        return response()->json(["msg" => "Update successful!", "code" => 200]);
    }

    public function computeRating($id){
        $sp = ClientSchedule::leftjoin('schedules', 'client_schedules.schedule_id', '=', 'schedules.id')
            ->leftjoin('services', 'schedules.service_id', '=', 'services.id')
            ->where('client_schedules.status', '=', 3)
            ->where('services.service_provider_id', '=', $id)
            ->select('client_schedules.rating')
            ->get();

        $spConvert = json_decode(json_encode($sp));
        $average = 0;

        foreach ($spConvert as $key => $value) {
            $average += $value->rating == null ? 0 : $value->rating;
        }


        $out = array();
        $out['totalRateCount'] = $sp->count();
        $out['totalRateRaw'] = $average;
        $out['average'] = $sp->count() == 0 ? 5 : round(($average/$sp->count()), 2);


        return response()->json($out);
    }


    public function destroy($id)
    {
        //
    }
}
