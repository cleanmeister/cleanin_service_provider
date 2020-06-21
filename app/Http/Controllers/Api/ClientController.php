<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\User as Client;
use App\Http\Resources\User as UserResource;
use App\Models\ServiceProvider;
use App\Models\BlockedUser;
use App\Http\Resources\ServiceProviderBooking as ServiceProviderBookingResource;

class ClientController extends Controller
{
    public function index(){
    	$word = trim(request()->word);

        $SPID = ServiceProvider::where('owner_id', Auth::user()->id)->first();
        $IDs = DB::table('services')->where('services.service_provider_id', $SPID->id)
            ->leftjoin('schedules', 'schedules.service_id', '=', 'services.id')
            ->leftjoin('client_schedules', 'client_schedules.schedule_id', '=', 'schedules.id')
            ->where('client_schedules.client_id', '!=', null)
            ->groupBy('client_schedules.client_id')
            ->select("client_schedules.client_id")->get();

    	$data = Client::whereHas('profile', function($query) use ($word){
    		$query->where('firstname', 'like', '%'.$word.'%')
    		->orWhere('middlename', 'like', '%'.$word.'%')
    		->orWhere('lastname', 'like', '%'.$word.'%');
    	})
        ->where('role_id', 4)
        ->where('is_active', 1)
        ->whereIn('id', json_decode(json_encode($IDs), true))
        ->get();

    	$clients = UserResource::collection($data);/**/
        $clients = json_decode(json_encode($clients), true);
        foreach ($clients as $key => $value) {
            $tmp = BlockedUser::where('blocked_id', $value['id'])->where('blocker_id', $SPID->id)->get();
            $clients[$key]['Blocked'] = (count(json_decode(json_encode($tmp), true)) > 0);
        }

    	return response()->json($clients);
    }

}
