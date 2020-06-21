<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientSchedule;
use App\Models\BlockedUser;
use Auth;
use App\Http\Resources\ClientSchedule as ClientScheduleResource;
use App\Http\Resources\CleanerClientSchedule as CleanerClientScheduleResource;
use DB;
use App\user;
use App\Models\Notification;
use Carbon\Carbon;

class ClientScheduleController extends Controller
{
    public  function index(){

    }

    public function store(){

    }

    public function show($id){

    }

    public function update(Request $request, $id){

    }

    public function destroy($id){

    }

    public function get_client_schedules(){
    	$client_id = Auth::user()->id;

    	$data = ClientSchedule::where('client_id', $client_id)->where("status", "!=", 6)->get();
        $cs = ClientScheduleResource::collection($data);
        $cs = json_decode(json_encode($cs));

        $out = array();
        foreach ($cs as $key => $value) {
            $tmp = DB::SELECT("SELECT COUNT(id) AS ctr FROM complaints WHERE client_schedule_id = ".$value->id." AND complainant_id = ".$client_id.";");
            foreach ($value as $keys => $values) {
                $out[$key][$keys] = $values;
            }
            $out[$key]["blocked"] = ($tmp[0]->ctr > 0);
        }
        
    	return response()->json($out);
    }

    public function get_cleaner_schedules(){
    	$cleaner_id = Auth::user()->id;

    	$data = DB::table('schedules')
        ->join('days', 'days.id', '=', 'schedules.day_id')
    	->join('client_schedules AS CS', 'CS.schedule_id', '=', 'schedules.id')
    	->join('users as client', 'CS.client_id', '=', 'client.id')
    	->join('user_profiles as profile', 'profile.user_id', '=', 'client.id')
    	->join('services', 'services.id', '=', 'schedules.service_id')
    	->where('schedules.cleaner_id', $cleaner_id)
        ->where('schedules.available', '!=', 2)
        ->select(
    		'schedules.id as schedule_id', 
    		'CS.id as id',
    		'client.id as client_id',
            'client.email as email',
    		'profile.firstname as client_firstname', 
    		'profile.middlename as client_middlename', 
    		'profile.lastname as client_lastname',
    		'services.id as service_id',
    		'services.name as service_name',
    		'services.desc as service_desc',
    		'schedules.start_time',
    		'schedules.end_time',
    		'CS.status',
            'CS.rating',
            'CS.rating_client',
            'CS.feedback',
            'CS.time_in',
            'CS.time_out',
            'CS.sched_start_time',
            'CS.sched_end_time',
            'days.desc as day_desc',
            'CS.landmark_address',
            'CS.id as CSID'
    	)->get();

    	$schedules = CleanerClientScheduleResource::collection($data);

    	return response()->json($schedules);
    }
//1 pending 2 approved  3 completed 4 rejected 5 cancel 6 remove
    public function approve($id){
        $cs = ClientSchedule::findOrFail($id);
        $msg = array();
        if($cs->status === 1){
            $cs->update([
                'status' => 2
            ]);

            $cs->schedule()->update([
                'available' => 0
            ]);

            Notification::create([
                'sender_id' => $cs->schedule->cleaner_id,
                'notification' => 'Cleaner <strong>'.$cs->schedule->cleaner->profile->firstname.' '.$cs->schedule->cleaner->profile->middlename.' '.$cs->schedule->cleaner->profile->lastname.'</strong> accepted your booking request for '.$cs->schedule->service->name.' on '.$cs->day->desc.' '.Carbon::parse($cs->sched_start_time)->format('h:i A').'-'.Carbon::parse($cs->sched_end_time)->format('h:i A').'.',
                'receiver_id' => $cs->client_id,
            ]);

            $msg = array("msg" => "Client request accepted succesfully!", "code" => 200);
        }else{
            switch ($cs->status) {
                case 2:
                    $msg = array("msg" => "Unable to accept request. Schedule has already been approved!", "code" => 500);
                    break;
                case 3:
                    $msg = array("msg" => "Unable to accept request. Transaction is already complete!", "code" => 500);
                    break;
                case 4:
                    $msg = array("msg" => "Unable to accept request. Transaction has already been rejected!", "code" => 500);
                    break;
                case 5:
                    $msg = array("msg" => "Unable to accept request. Transaction has been canceled by the client!", "code" => 500);
                    break;
                default:
                    $msg = array("msg" => "Unable to accept request. unkown error occured!", "code" => 500);
                    break;
            }
        }
        return response()->json($msg);
    }

    public function reject($id){
        $cs = ClientSchedule::findOrFail($id);
        $cs->update([
            'status' => 4
        ]);

        $cs->schedule()->update([
            'available' => 1
        ]);

        Notification::create([
            'sender_id' => $cs->schedule->cleaner_id,
            'notification' => 'Cleaner declined your booking request for '.$cs->schedule->service->name.' on '.$cs->day->desc.' '.Carbon::parse($cs->sched_start_time)->format('h:i A').'-'.Carbon::parse($cs->sched_end_time)->format('h:i A').'.',
            'receiver_id' => $cs->client_id,
        ]);

        Notification::create([
            'sender_id' => $cs->client_id,
            'notification' => 'Cleaner declined clients booking request for '.$cs->schedule->service->name.' on '.$cs->day->desc.' '.Carbon::parse($cs->sched_start_time)->format('h:i A').'-'.Carbon::parse($cs->sched_end_time)->format('h:i A').'.',
            'receiver_id' => $cs->schedule->service->service_provider->owner_id,
        ]);

        $msg = array("msg" => "Request succesful!", "code" => 200);
        return response()->json($msg);
    }

    public function cancel($id){
        $cs = ClientSchedule::findOrFail($id);
        $cs->update([
            'status' => 5
        ]);

        Notification::create([
            'sender_id' => $cs->client_id,
            'notification' => 'Client cancelled booking on '.$cs->schedule->service->name.' '.Carbon::parse($cs->sched_start_time)->format('h:i A').'-'.Carbon::parse($cs->sched_end_time)->format('h:i A').'.',
            'receiver_id' => $cs->schedule->cleaner_id,
        ]);

        $cs->schedule()->update([
            'available' => 1
        ]);

        $msg = array("msg" => "Request succesful!", "code" => 200);
        return response()->json($msg);
    }

    public function remove($id){
        $cs = ClientSchedule::where('id', $id)->first();

        $cs->schedule()->update([
            'available' => 1
        ]);

        $cs->update([
            'status' => 6
        ]);

        Notification::create([
            'sender_id' => $cs->client_id,
            'notification' => 'Client removed booking on '.$cs->schedule->service->name.' '.Carbon::parse($cs->sched_start_time)->format('h:i A').'-'.Carbon::parse($cs->sched_end_time)->format('h:i A').'.',
            'receiver_id' => $cs->schedule->cleaner_id,
        ]);

        $msg = array("msg" => "Request succesful!", "code" => 200);
        return response()->json($msg);
    }   

    public function time_in(Request $request, $id){
        $request->validate([
            'time_in' => 'required'
        ]);

        $cs = ClientSchedule::findOrFail($id);
        $cs->update([
            'time_in' => $request->time_in,
        ]);

        $role_id = user::where('id', Auth::user()->id)->first()->role_id;
        if($role_id == 2){
            Notification::create([
                'sender_id' => Auth::user()->id,
                'notification' => 'Service Provider timed in cleaner '.$cs->schedule->service->name.' at '.Carbon::parse($cs->time_in)->format('h:i A').'.',
                'receiver_id' => $cs->client_id,
            ]);
            Notification::create([
                'sender_id' => Auth::user()->id,
                'notification' => 'Service Provider timed you in for '.$cs->schedule->service->name.' at '.Carbon::parse($cs->time_in)->format('h:i A').'.',
                'receiver_id' => $cs->schedule->cleaner_id,
            ]);
        }else{
            Notification::create([
                'sender_id' => $cs->client_id,
                'notification' => 'Client timed you in for '.$cs->schedule->service->name.' at '.Carbon::parse($cs->time_in)->format('h:i A'),
                'receiver_id' => $cs->schedule->cleaner_id,
            ]);
            Notification::create([
                'sender_id' => $cs->client_id,
                'notification' => 'Client timed in cleaner for '.$cs->schedule->service->name.' at '.Carbon::parse($cs->time_in)->format('h:i A'),
                'receiver_id' => $cs->schedule->service->service_provider->owner_id,
            ]);
        }
        return response()->json(['msg'=>'Time in Successful!', 'code' => 200]);
    }

    public function time_out(Request $request, $id){
        $request->validate([
            'time_out' => 'required'
        ]);
        $cs = ClientSchedule::findOrFail($id);
        
        $cs->update([
            'status' => 3,
            'time_out' => $request->time_out,
        ]);

        $cs->schedule()->update([
            'available' => 1
        ]);

        $role_id = user::where('id', Auth::user()->id)->first()->role_id;
        if($role_id == 2){
            Notification::create([
                'sender_id' => Auth::user()->id,
                'notification' => 'Transaction for '.$cs->schedule->service->name.' was completed by the service provider at '.Carbon::parse($cs->time_out)->format('h:i A').'.',
                'receiver_id' => $cs->client_id,
            ]);
            Notification::create([
                'sender_id' => Auth::user()->id,
                'notification' => 'Transaction for '.$cs->schedule->service->name.' was completed by the service provider at '.Carbon::parse($cs->time_out)->format('h:i A').'.',
                'receiver_id' => $cs->schedule->cleaner_id,
            ]);
        }else{

            Notification::create([
                'sender_id' => $cs->schedule->cleaner_id,
                'notification' => 'Transaction completed, Thank you for using our service.',
                'receiver_id' => $cs->client_id,
            ]);
            Notification::create([
                'sender_id' => $cs->client_id,
                'notification' => 'Client timed you out for '.$cs->schedule->service->name.' at '.Carbon::parse($cs->time_out)->format('h:i A').' completing the transaction.',
                'receiver_id' => $cs->schedule->cleaner_id,
            ]);
            Notification::create([
                'sender_id' => $cs->client_id,
                'notification' => 'Client timed out cleaner for '.$cs->schedule->service->name.' at '.Carbon::parse($cs->time_out)->format('h:i A').' completing the transaction.',
                'receiver_id' => $cs->schedule->service->service_provider->owner_id,
            ]);
        }


        return response()->json(['msg'=>'Time in Successful!', 'code' => 200]);
    }

    public function rate(Request $request, $id){
        $this->validate($request,
            [ 'rate' => 'required' ],
            [ 'rate.required' => 'Please <b class="text text-danger">Rate</b> our cleaner!' ]
        );

        $cs = ClientSchedule::findOrFail($id);

        $cs->update([
            'rating' => $request->rate,
            'feedback' => $request->feedback,
        ]);

        Notification::create([
            'sender_id' => $cs->client_id,
            'notification' => 'Client rated your service for '.$cs->schedule->service->name,
            'receiver_id' => $cs->schedule->cleaner_id,
        ]);

        return response()->json(['msg'=>'Success!', 'code' => 200]);
    }

    public function rate_client(Request $request, $id){
        $this->validate($request,
            [ 'rating' => 'required' ],
            [ 'rating.required' => 'Please <b class="text text-danger">Rate</b> our customer!' ]
        );

        $cs = ClientSchedule::findOrFail($id);
        $cs->update([
            'rating_client' => $request->rating,
            'feedback_client' => $request->feedback,
        ]);
        Notification::create([
            'sender_id' => $cs->schedule->cleaner_id,
            'notification' => 'Cleaner gave you a '.$request->rate.' star rating for '.$cs->schedule->service->name,
            'receiver_id' => $cs->client_id,
        ]);

        return response()->json(['msg'=>'Success!', 'code' => 200]);
    }


}
