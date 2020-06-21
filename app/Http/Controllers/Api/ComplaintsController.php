<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Notification;
use App\Models\Day;
use App\complaints;
use App\Http\Resources\User as UserResource;
use App\Models\ClientSchedule;
use App\Http\Resources\ClientSchedule as ClientScheduleResource;
use App\Http\Resources\Complaints as ComplaintsResource;
use App\Models\ServiceProvider;
use App\User as Client;
use Carbon\Carbon;

class ComplaintsController extends Controller
{
    public function index(){

    }

    public function complain(Request $request){
        $id = Auth::user()->id;

        $this->validate($request, [
            'reason' => 'required|max:255',
            'CSid' => 'required',
            'CleanID' => 'required'
        ],[
            'CSid.required' => 'Unable to process your request at this moment.',
            'CleanID.required' => 'Unable to process your request at this moment.'
        ]);

        $Reported = DB::SELECT("SELECT id FROM complaints WHERE client_schedule_id = ".$request->CSid." AND complainant_id = ".$id." AND complainee_id = ".$request->CleanID.";");

        if(count($Reported) > 0){
            $msg = array("msg" => "A report has already been submitted for this transaction.", "code" => 500);
            return response()->json($msg);
        }

        $block = complaints::create([
            'complainant_id' => $id,
            'complainee_id' => $request->CleanID,
            'client_schedule_id' => $request->CSid,
            'complain' => $request->reason
        ]);

        //$cs = DB::table('client_schedules')->first();
        $cs = ClientSchedule::findOrFail($request->CSid);
        $cs->update([ 'rating' => null ]);
        if(Auth::user()->role_id == 3){ // is cleaner
            Notification::create([
                'sender_id' => Auth::user()->id,
                'notification' => 'Cleaner sent a complaint to client for '.$cs->schedule->service->name.' '.Carbon::parse($cs->sched_start_time)->format('h:i A').'-'.Carbon::parse($cs->sched_end_time)->format('h:i A').'.',
                'receiver_id' => $cs->schedule->service->service_provider->owner_id,
            ]);
        }else if(Auth::user()->role_id ==4){ // is client
            Notification::create([
                'sender_id' => $cs->client_id,
                'notification' => 'Client sent a complaint to your service for '.$cs->schedule->service->name.' '.Carbon::parse($cs->sched_start_time)->format('h:i A').'-'.Carbon::parse($cs->sched_end_time)->format('h:i A').'.',
                'receiver_id' => $cs->schedule->cleaner_id,
            ]);

            Notification::create([
                'sender_id' => $cs->client_id,
                'notification' => 'Client sent a complaint to cleaner for '.$cs->schedule->service->name.' '.Carbon::parse($cs->sched_start_time)->format('h:i A').'-'.Carbon::parse($cs->sched_end_time)->format('h:i A').'.',
                'receiver_id' => $cs->schedule->service->service_provider->owner_id,
            ]);
        }

        $msg = array("msg" => "<div><h2>Report succesfully submitted!</h2><h5>Thank Your for your feedback!</h5><div>", "code" => 200);
        return response()->json($msg);
    }

    //this class needs to be simplified
    public function CollectComplains(){
        $word = request()->word;
        $id = Auth::user()->id;
        $out = array();
        $SPID = ServiceProvider::where('owner_id', Auth::user()->id)->first();


        $IDs = DB::table('services')->where('services.service_provider_id', $SPID->id)
            ->leftjoin('schedules', 'schedules.service_id', '=', 'services.id')
            ->leftjoin('client_schedules', 'client_schedules.schedule_id', '=', 'schedules.id')
            ->where('client_schedules.client_id', '!=', null)
            //->groupBy('client_schedules.client_id')
            ->select("client_schedules.id")->get()->toArray();
        //$tmp = array_map(function ($value) { return (array)$value; }, $IDs);


        $complaints = complaints::with('complainant')
            ->with('complainee')
            ->with('client_schedule')
            ->whereIn('client_schedule_id', json_decode(json_encode($IDs), true))
            ->orderBy('created_at')
            ->get();

        $complains = ComplaintsResource::collection($complaints);
        $complains = json_decode(json_encode($complaints), true);

        /*$data = Client::whereHas('profile', function($query) use ($word){
            $query->where('firstname', 'like', '%'.$word.'%')
            ->orWhere('middlename', 'like', '%'.$word.'%')
            ->orWhere('lastname', 'like', '%'.$word.'%');
        })
        ->whereIn('users.role_id', array(3,4))
        ->whereIn('users.id', json_decode(json_encode($IDs), true))
        ->select('users.id')->get();*/

        /*$data = DB::table('users')
        ->leftjoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
        ->where('users.role_id', 4)
        ->whereIn('users.id', json_decode(json_encode($IDs), true))
        ->select(
            'users.id AS id',
            'users.is_active',
            'user_profiles.firstname',
            'user_profiles.lastname',
            'user_profiles.address',
            'user_profiles.mobile_number'
        )->get();*/

        //$complainant = DB::table('complaints')->leftjoin('user_profiles', )

        /*$complaints = complaints::with('complainant')
            ->with('client_schedule')
            ->whereIn('complainant_id', json_decode(json_encode($IDs), true))
            ->orderBy('created_at')
            ->get();*/
        //$complains = ComplaintsResource::collection($complaints);
       /* $complains = json_decode(json_encode($complaints), true);*/
        foreach ($complains as $key => $value) {
            $customer = DB::table('user_profiles')
                ->where('user_id', $value['complainant']['id'])
                ->select('firstname', 'middlename', 'lastname')
                ->first();

            $complainee  = DB::table('user_profiles')
                ->where('user_id', $value['complainee']['id'])
                ->select('firstname', 'middlename', 'lastname')
                ->first();

            $cleaner = DB::table('client_schedules AS cs')
                ->join('schedules', 'cs.schedule_id', '=', 'schedules.id')
                ->join('user_profiles', 'schedules.cleaner_id', '=', 'user_profiles.user_id')
                ->join('services', 'services.id', '=', 'schedules.service_id')
                ->where('cs.id', $value['client_schedule_id'])
                ->select('user_profiles.firstname',
                    'user_profiles.middlename',
                    'user_profiles.lastname',
                    'services.name as ServiceName',
                    'schedules.start_time',
                    'schedules.end_time',
                    'schedules.day_id',
                    'cs.sched_start_time',
                    'cs.sched_end_time'
                )
                ->first();
            $cleanerResource = json_decode(json_encode($cleaner), true);
            //$out[$key]['test'] = $value;
            $out[$key]['complaint'] = $value['complain'];
            $out[$key]['time_in'] = Carbon::createFromFormat('H:i:s', $value['client_schedule']['time_in'])->format('g:i A');
            $out[$key]['time_out'] = Carbon::createFromFormat('H:i:s', $value['client_schedule']['time_out'])->format('g:i A');
            $out[$key]['start_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $cleaner->sched_start_time)->format('F d, Y, g:i A');
            $out[$key]['end_time'] = Carbon::createFromFormat('Y-m-d H:i:s', $cleaner->sched_end_time)->format('g:i A');
            $out[$key]['ServiceName'] = $cleanerResource['ServiceName'];
            $out[$key]['day'] = Day::where('id', $cleanerResource['day_id'])->select('desc')->first()->desc;
            $out[$key]['complainant'] = json_decode(json_encode($customer), true);
            $out[$key]['complainant']['fullname'] = $out[$key]['complainant']['firstname'].' '.$out[$key]['complainant']['middlename'].' '.$out[$key]['complainant']['lastname'];
            $out[$key]['complainee'] = json_decode(json_encode($complainee), true);
            $out[$key]['complainee']['fullname'] = $out[$key]['complainee']['firstname'].' '.$out[$key]['complainee']['middlename'].' '.$out[$key]['complainee']['lastname'];


                //$out[$key]['customer'] = json_decode(json_encode($customer), true);
                //$out[$key]['customer']['fullname'] = $out[$key]['customer']['firstname'].' '.$out[$key]['customer']['middlename'].' '.$out[$key]['customer']['lastname'];
                //$out[$key]['cleaner'] = json_decode(json_encode($cleaner), true);
                //$out[$key]['cleaner']['fullname'] = $out[$key]['cleaner']['firstname'].' '.$out[$key]['cleaner']['middlename'].' '.$out[$key]['cleaner']['lastname'];
                
        }
        
        return response()->json($out);

    }

}
