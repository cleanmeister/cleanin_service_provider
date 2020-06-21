<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ClientSchedule;
use App\Models\Schedule;
use App\Models\Notification;
use App\Models\ServiceProvider;
use App\Models\Day;
use Auth;
use DB;
use App\Http\Resources\ServiceProviderBooking as ServiceProviderBookingResource;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(){

    }

    public function store(Request $request){
    	$client_id = Auth::user()->id;

        $this->validate(
            $request, 
            [
                'landmark_address' => 'required|max:255',
                'schedule_id' => 'required'
            ],
            [
                'landmark_address.required' => 'The <b class="text text-danger">Landmark Address</b> field is required.',
                'landmark_address.max' => 'The <b class="text text-danger">Landmark Address</b> field exceeds the maximum(255) allowed length of characters.',
                'schedule_id.required' => 'Please select a <b class="text text-danger">Schedule</b> for your desired service.'
            ]
        );

        $schedule = Schedule::where('id', $request->schedule_id)->first();
        $day = Day::where('id', $schedule->day_id)->first();
        $date = Carbon::now()->next($day->desc)->format('Y-m-d');
        $start_time = Carbon::createFromFormat('H:i:s',$schedule->start_time);
        $end_time = Carbon::createFromFormat('H:i:s',$schedule->end_time);
    	/**/
        $cs = ClientSchedule::create([
    		'client_id' => $client_id,
    		'schedule_id' => $request->schedule_id,
    		'status' => 1,
    		'landmark_address' => $request->landmark_address,
            'sched_start_time' => Carbon::parse($date)->setTime($start_time->hour, $start_time->minute),
            'sched_end_time' => Carbon::parse($date)->setTime($end_time->hour, $end_time->minute),
            'day_id' => $schedule->day_id
    	]);

        $cs->schedule()->update([
            'available' => 0
        ]);
        
        $receiver_id = $cs->schedule->cleaner_id;
        $service_name = $cs->schedule->service->name;

        $notif = Notification::create([
            'sender_id' => $client_id,
            'notification' => 'Requests for '.$service_name,
            'receiver_id' => $receiver_id,
        ]);

        $notif = Notification::create([
            'sender_id' => $client_id,
            'notification' => 'Requests for '.$service_name,
            'receiver_id' => $cs->schedule->service->service_provider->owner_id,
        ]);

    	return response()->json(array('msg' => "Booked service successfully!", "code" => 200, $schedule));
    }

    public function update(Request $request, $id){

    }

    public function decline($id){
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

    public function service_provider_booking(){

        $service_provider = ServiceProvider::where('owner_id', Auth::user()->id)->first();
        $service_provider_id = $service_provider->id;

        $data = DB::table('schedules as sc')
        ->leftjoin('client_schedules as cs', 'cs.schedule_id', '=', 'sc.id')
        ->leftjoin('services as s', 's.id', '=', 'sc.service_id')
        ->leftjoin('user_profiles as cleaner', 'cleaner.user_id', '=', 'sc.cleaner_id')
        ->leftjoin('user_profiles as client', 'client.user_id', '=', 'cs.client_id')
        ->leftjoin('users', 'client.user_id', '=', 'users.id')
        ->leftjoin('days as d', 'd.id', '=', 'sc.day_id')   
        ->where('s.service_provider_id', '=', $service_provider_id)
        ->where('cs.status', '<>', null)
        ->select('sc.service_id',
            's.name as service_name', 
            'cleaner.firstname as cleaner_firstname', 
            'cleaner.middlename as cleaner_middlename', 
            'cleaner.lastname as cleaner_lastname',
            'client.firstname as client_firstname',
            'client.middlename as client_middlename', 
            'client.lastname as client_lastname', 
            'users.email as client_email',
            'sc.start_time', 
            'sc.end_time',
            'cs.rating',
            'cs.feedback',
            'cs.id AS client_schedule_id',
            'cs.status as status',
            'cs.time_in',
            'cs.time_out',
            'cs.sched_start_time',
            'cs.sched_end_time',
            'd.desc as day_desc',
            'sc.day_id')->get();
        $service_provider_bookings = ServiceProviderBookingResource::collection($data);

        return response()->json($service_provider_bookings);
    }
}
