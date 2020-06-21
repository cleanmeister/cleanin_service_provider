<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlockedUser;
use App\Models\ClientSchedule;
use Auth;
use DB;
use App\Models\Notification;
use App\Http\Resources\ClientSchedule as ClientScheduleResource;
use App\Models\ServiceProvider;

class BlockedUserController extends Controller
{
    public function index(){


    }

    public function store(Request $request){
        //if(Auth::user()->id == 2){
            $this->validate($request, [
                'blocked_id' => 'required'
            ],[
                'blocked_id.required' => 'Unable to process your request at this moment.'
            ]);
            $SPID = ServiceProvider::where('owner_id', Auth::user()->id)->first();
            $Reported = DB::SELECT("SELECT blocked_id FROM blocked_users WHERE blocked_id = ".$request->blocked_id." AND blocker_id = ".$SPID->id.";");
            if(count($Reported) > 0){
                $msg = array("msg" => "This user has already been blocked.", "code" => 422);
                return response()->json($msg, 422);
            }
            if($request->reason == '' || $request->reason == null) $request->reason = 'N/A';
        /*}else{
            $this->validate($request, [
                'blocked_id' => 'required',
                'reason' => 'required'
            ],[
                'blocked_id.required' => 'Unable to process your request at this moment.'
            ]);
        }*/
    	$block = BlockedUser::create([
    		'blocker_id' => $SPID->id,
    		'blocked_id' => $request->blocked_id,
    		'reason' => $request->reason
    	]);
        $msg = array("msg" => "<div><h2>Block successful!</h2><h5>User can no longer book our services!</h5><div>", "code" => 200, "Rold" =>Auth::user()->id);
    	return response()->json($msg);
    }

    public function unblock(Request $request){

        $this->validate($request, [
            'unblocked_id' => 'required'
        ],[
            'unblocked_id.required' => 'Unable to process your request at this moment.'
        ]);

        $SPID = ServiceProvider::where('owner_id', Auth::user()->id)->first();
        $unban = BlockedUser::where('blocker_id', $SPID->id)->where('blocked_id', $request->unblocked_id)->delete();

        $msg = array("msg" => "<div><h2>Unblock successful!</h2><h5>User can now book our services!</h5><div>", "code" => 200);
        return response()->json($msg);
    }

    public function destroy($id){
    }
}
