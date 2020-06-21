<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\Models\Schedule;
use App\Http\Resources\Schedule as ScheduleResource;
use App\CustomHelpers\ScheduleClass as ScheduleClass;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;

class ScheduleController extends Controller
{

    public function index()
    {
        $cleaner_id = Auth::user()->id;

        $data = Schedule::where('cleaner_id', $cleaner_id)->where('available', '!=', 2)->orderBy('start_time', 'asc')->get();
        $schedules = ScheduleResource::collection($data);
        return response()->json($schedules);
    }


    public function check(){

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "end_time" => "required",
            "start_time" => "required",
            "service_id" => "required",
            "days" => "required"
        ],[
            "service_id.required" => "<h5>Please select a <b class=\"text text-danger\">Service</b> for this schedule.</h5>"
        ]);

        $utc = new DateTimeZone('UTC');

        $start = new DateTime($request->start_time,$utc);
        $end = new DateTime($request->end_time,$utc);

        $Error[] = array(
            "msg" => 'Some Schedules not added.',
            "errors" => array(),
            "code" => 500
        );

        if($end < $start){
            $Error[0]['errors'][] = "<b class=\"text text-danger\">Start time</b> should not be later than the <b class=\"text text-danger\">End time</b>!";
            return response()->json($Error);
        }

        $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        $msg = array();
        $cleaner_id = Auth::id();
        $day = $request->days;

        /*foreach ($day as $key => $value) {
            $getSchedules = Schedule::with('service')
                ->where('cleaner_id',$cleaner_id)
                ->where('day_id', $value)
                ->where('available', '!=', 2)
                ->get();
            $tmp = false;
            foreach ($getSchedules as $keys => $values)
            {
                $DBstart_time_format = date('h:i a', strtotime($values->start_time));
                $DBend_time_format = date('h:i a', strtotime($values->end_time));
                $start_time_format = date('h:i a', strtotime($request->start_time));
                $end_time_format = date('h:i a', strtotime($request->end_time));

                if($this->SameTimeRange($values->start_time, $values->end_time, $request->start_time, $request->end_time)){
                    $tmp = true;
                    $Error[$k]["errors"][] = "Existing Schedule for " . $values["service"]["name"] . " on " . $days[$value - 1] . '<br> New: '.  $start_time_format . ' - ' . $end_time_format . '<br> Existing:' . $DBstart_time_format . ' - ' . $DBend_time_format;
                    break;
                //}if(!$this->rangesNotOverlapOpen($DBstart_time_format, $DBend_time_format, $start_time_format, $end_time_format)){
                //}if(!$this->rangesNotOverlapOpen($values->start_time, $values->end_time, $request->start_time, $request->end_time)){
                }else if($this->checkIfOverlapped($DBstart_time_format, $DBend_time_format, $start_time_format, $end_time_format)){
                    $tmp = true;
                    $Error[$k]["errors"][] = "Overlapping Schedule for " . $values["service"]["name"] . " on " . $days[$value - 1] . '<br> New: '.  $start_time_format . ' - ' . $end_time_format . '<br> Existing:' . $DBstart_time_format . ' - ' . $DBend_time_format;
                    break;
                }
            }
            $k++;
            if(!$tmp){
                $user = Schedule::firstOrCreate([
                    'cleaner_id' => $cleaner_id,
                    'day_id' => $value,
                    'service_id'=> $request->service_id,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'available' => 1
                ]);
            }
        }*/

        foreach ($day as $key => $value) {
            $getSchedules = Schedule::with('service')
                ->where('cleaner_id',$cleaner_id)
                ->where('day_id', $value)
                ->where('available', '!=', 2)
                ->get();
            $tmp = false;
            foreach ($getSchedules as $keys => $values)
            {
                $DBstart_time_format = date('h:i a', strtotime($values->start_time));
                $DBend_time_format = date('h:i a', strtotime($values->end_time));
                $start_time_format = date('h:i a', strtotime($request->start_time));
                $end_time_format = date('h:i a', strtotime($request->end_time));
                if($this->SameTimeRange($values->start_time, $values->end_time, $request->start_time, $request->end_time)){
                    $tmp = true;
                    $Error[0]["errors"][] = "Existing Schedule for " . $values["service"]["name"] . " on " . $days[$value - 1] . '<br> New: '.  $start_time_format . ' - ' . $end_time_format . '<br> Existing:' . $DBstart_time_format . ' - ' . $DBend_time_format;
                    break;
                //}if(!$this->rangesNotOverlapOpen($DBstart_time_format, $DBend_time_format, $start_time_format, $end_time_format)){
                //}if(!$this->rangesNotOverlapOpen($values->start_time, $values->end_time, $request->start_time, $request->end_time)){
                }if($this->checkIfOverlappedClosed($DBstart_time_format, $DBend_time_format, $start_time_format, $end_time_format)){
                    $tmp = true;
                    $Error[0]["errors"][] = "Overlapping Schedule for " . $values["service"]["name"] . " on " . $days[$value - 1] . '<br> New: '.  $start_time_format . ' - ' . $end_time_format . '<br> Existing:' . $DBstart_time_format . ' - ' . $DBend_time_format;
                    break;
                }
            }
            if(!$tmp){
                $user = Schedule::firstOrCreate([
                    'cleaner_id' => $cleaner_id,
                    'day_id' => $value,
                    'service_id'=> $request->service_id,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'available' => 1
                ]);
            }
        }
        if(count($Error[0]['errors']) > 0){
            $msg = $Error;
        }else{
            $msg[] = array(
                "msg" => 'Schedule updated',
                "code" => 200
            );
        }
        return response()->json($msg);
    }
    function SameTimeRange($start_time1,$end_time1,$start_time2,$end_time2){
        $utc = new DateTimeZone('UTC');

        $start1 = new DateTime($start_time1,$utc);
        $end1 = new DateTime($end_time1,$utc);
        $start2 = new DateTime($start_time2,$utc);
        $end2 = new DateTime($end_time2,$utc);

        return (($start1 == $start2) && ($end1 == $end2));
    }

    function rangesNotOverlapClosed($start_time1,$end_time1,$start_time2,$end_time2)
    {
        $utc = new DateTimeZone('UTC');

        $start1 = new DateTime($start_time1,$utc);
        $end1 = new DateTime($end_time1,$utc);
        if($end1 < $start1)
        {
            //$start1 = new DateTime($end_time1,$utc);
            //$end1 = new DateTime($start_time1,$utc);
            //throw new Exception('Range is negative.');
        }

        $start2 = new DateTime($start_time2,$utc);
        $end2 = new DateTime($end_time2,$utc);
        if($end2 < $start2)
        {
            //$start2 = new DateTime($start_time2,$utc);
            //$end2 = new DateTime($end_time2,$utc);
            //throw new Exception('Range is negative.');
        }

        return ($end1 < $start2) || ($end2 < $start1);
    }

    function rangesNotOverlapOpen($start_time1,$end_time1,$start_time2,$end_time2)
    {
        $utc = new DateTimeZone('UTC');

        $start1 = new DateTime($start_time1,$utc);
        $end1 = new DateTime($end_time1,$utc);
        if($end1 < $start1){
            //$start1 = new DateTime($end_time1,$utc);
            //$end1 = new DateTime($start_time1,$utc);
            //throw new Exception('Range is negative.');
            $end1->add(new \DateInterval("P1D"));//add 1 day
        }

        $start2 = new DateTime($start_time2,$utc);
        $end2 = new DateTime($end_time2,$utc);
        if($end2 < $start2){
            //$start2 = new DateTime($start_time2,$utc);
            //$end2 = new DateTime($end_time2,$utc);
            //throw new Exception('Range is negative.');
            $end2->add(new \DateInterval("P1D"));//add 1 day
        }

        //return (($end1 <= $start2) || ($end2 <= $start1));
        return ($start1 <= $end2) && ($start2 <= $end1);
    }
    // pass your ranges to this method and if there is a common intersecion it will
    // return it or false

    function checkIfOverlapped($r1s_, $r1e_, $r2s_, $r2e_)
    {
        $r1s = new DateTime($r1s_);
        $r1e = new DateTime($r1e_);
        $r2s = new DateTime($r2s_);
        $r2e = new DateTime($r2e_);
        if($r2e < $r2s){
            $r2e->add(new \DateInterval("P1D"));
            if($r1e < $r1s) $r1e->add(new \DateInterval("P1D"));
        }
        if ($r1s >= $r2s && $r1s <= $r2e || $r1e >= $r2s && $r1e <= $r2e || $r2s >= $r1s && $r2s <= $r1e || $r2e >= $r1s && $r2e <= $r1e) {
            return true;
        } else return false;
    }

    function checkIfOverlappedClosed($r1s_, $r1e_, $r2s_, $r2e_)
    {
        $r1s = new DateTime($r1s_);
        $r1e = new DateTime($r1e_);
        $r2s = new DateTime($r2s_);
        $r2e = new DateTime($r2e_);
        if($r2e < $r2s){
            $r2e->add(new \DateInterval("P1D"));
            if($r1e < $r1s) $r1e->add(new \DateInterval("P1D"));
        }
        if ($r1s > $r2s && $r1s < $r2e || $r1e > $r2s && $r1e < $r2e || $r2s > $r1s && $r2s < $r1e || $r2e > $r1s && $r2e < $r1e) {
            return true;
        } else return false;
    }

    public function update(Request $request, $id)
    {        
        $this->validate($request, [
            "end_time" => "required",
            "start_time" => "required",
            "service_id" => "required",
            "days" => "required"
        ],[
            "service_id.required" => "<h5>Please select a <b class='text text-danger'>Service</b> for this schedule.</h5>"
        ]);

        $utc = new DateTimeZone('UTC');

        $start = new DateTime($request->start_time,$utc);
        $end = new DateTime($request->end_time,$utc);

        $Error[] = array(
            "msg" => 'Some Schedules not added.',
            "errors" => array(),
            "code" => 500
        );

        if($end < $start){
            $Error[0]['errors'][] = "<b class=\"text text-danger\">Start time</b> should not be later than the <b class=\"text text-danger\">End time</b>!";
            return response()->json($Error);
        }

        $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        $msg = array();
        $cleaner_id = Auth::id();
        $day = $request->days;

        foreach ($day as $key => $value) {
            $getSchedules = Schedule::with('service')
                ->where('cleaner_id',$cleaner_id)
                ->where('id', '!=', $id)
                ->where('day_id', $value)
                ->where('available', '!=', 2)
                ->get();
            $tmp = false;
            foreach ($getSchedules as $keys => $values)
            {
                $DBstart_time_format = date('h:i a', strtotime($values->start_time));
                $DBend_time_format = date('h:i a', strtotime($values->end_time));
                $start_time_format = date('h:i a', strtotime($request->start_time));
                $end_time_format = date('h:i a', strtotime($request->end_time));
                if($this->SameTimeRange($values->start_time, $values->end_time, $request->start_time, $request->end_time)){
                    $tmp = true;
                    $Error[0]["errors"][] = "Existing Schedule for " . $values["service"]["name"] . " on " . $days[$value - 1] . '<br> New: '.  $start_time_format . ' - ' . $end_time_format . '<br> Existing:' . $DBstart_time_format . ' - ' . $DBend_time_format;
                    break;
                //}if(!$this->rangesNotOverlapOpen($DBstart_time_format, $DBend_time_format, $start_time_format, $end_time_format)){
                //}if(!$this->rangesNotOverlapOpen($values->start_time, $values->end_time, $request->start_time, $request->end_time)){
                }if($this->checkIfOverlappedClosed($DBstart_time_format, $DBend_time_format, $start_time_format, $end_time_format)){
                    $tmp = true;
                    $Error[0]["errors"][] = "Overlapping Schedule for " . $values["service"]["name"] . " on " . $days[$value - 1] . '<br> New: '.  $start_time_format . ' - ' . $end_time_format . '<br> Existing:' . $DBstart_time_format . ' - ' . $DBend_time_format;
                    break;
                }
            }
            if(!$tmp){
                Schedule::where('id', $id)->update([
                    'service_id' => $request->service_id,
                    'day_id' => $value,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                ]);
            }
        }
        if(count($Error[0]['errors']) > 0){
            $msg = $Error;
        }else{
            $msg[] = array(
                "msg" => 'Schedule updated',
                "code" => 200
            );
        }
        return response()->json($msg);
    }

    public function destroy($id)
    {
        //$clientSchedules = DB::SELECT("SELECT Count(id) AS ctr FROM client_schedules AS CS JOIN schedules AS S ON CS.schedule_id = S.id WHERE CS.schedule_id = $id AND CS.status = ;");

        $schedule = Schedule::where('id', $id)->update([ 'available' => 2 ]);
        $msg[] = array(
            "msg" => 'Successfully deleted Schedule.',
            "code" => 200
        );
        return response()->json($msg);
    }

    public function monday(){
        $cleaner_id = Auth::user()->id;

        $data = Schedule::where('cleaner_id', $cleaner_id)
        ->where('available', '!=', 2)
        ->where('day_id', 1)
        ->orderBy('start_time', 'asc')->get();

        $schedules = ScheduleResource::collection($data);

        return response()->json($schedules);
    }

    public function tuesday(){
        $cleaner_id = Auth::user()->id;

        $data = Schedule::where('cleaner_id', $cleaner_id)
        ->where('available', '!=', 2)
        ->where('day_id', 2)
        ->orderBy('start_time', 'asc')->get();

        $schedules = ScheduleResource::collection($data);

        return response()->json($schedules);
    }

    public function wednesday(){
        $cleaner_id = Auth::user()->id;

        $data = Schedule::where('cleaner_id', $cleaner_id)
        ->where('available', '!=', 2)
        ->where('day_id', 3)
        ->orderBy('start_time', 'asc')->get();

        $schedules = ScheduleResource::collection($data);

        return response()->json($schedules);
    }

    public function thursday(){
        $cleaner_id = Auth::user()->id;

        $data = Schedule::where('cleaner_id', $cleaner_id)
        ->where('available', '!=', 2)
        ->where('day_id', 4)
        ->orderBy('start_time', 'asc')->get();

        $schedules = ScheduleResource::collection($data);

        return response()->json($schedules);
    }

    public function friday(){
        $cleaner_id = Auth::user()->id;

        $data = Schedule::where('cleaner_id', $cleaner_id)
        ->where('available', '!=', 2)
        ->where('day_id', 5)
        ->orderBy('start_time', 'asc')->get();

        $schedules = ScheduleResource::collection($data);

        return response()->json($schedules);
    }

    public function saturday(){
        $cleaner_id = Auth::user()->id;

        $data = Schedule::where('cleaner_id', $cleaner_id)
        ->where('available', '!=', 2)
        ->where('day_id', 6)
        ->orderBy('start_time', 'asc')->get();

        $schedules = ScheduleResource::collection($data);

        return response()->json($schedules);
    }

    public function sunday(){
        $cleaner_id = Auth::user()->id;

        $data = Schedule::where('cleaner_id', $cleaner_id)
        ->where('available', '!=', 2)
        ->where('day_id', 7)
        ->orderBy('start_time', 'asc')->get();

        $schedules = ScheduleResource::collection($data);

        return response()->json($schedules);
    }
}
