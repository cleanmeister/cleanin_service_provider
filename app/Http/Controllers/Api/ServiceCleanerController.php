<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\Schedule;
use App\Http\Resources\Schedule as ScheduleResource;
use App\Http\Resources\CleanerSchedule;


class ServiceCleanerController extends Controller
{
    public function service_cleaners($service_id){
        $data = DB::SELECT("SELECT up.firstname, up.middlename, up.lastname, c.id, c.email, up.profile_pic, up.date_of_birth, csp.cleaner_restrictions
            FROM schedules as sc
            left outer join users as c on c.id = sc.cleaner_id
            left outer join cleaner_service_provider AS csp ON csp.cleaner_id = sc.cleaner_id
            left outer join user_profiles as up on up.user_id = c.id
            left outer join client_schedules as cs on cs.schedule_id = sc.id
            where sc.service_id = $service_id AND csp.status = 1 AND c.is_active = 1
            group by c.id, c.email, up.firstname, up.middlename, up.lastname, up.profile_pic, up.date_of_birth, csp.cleaner_restrictions;");
        $data = array_map(function ($value) { return (array)$value; }, $data);
        $out = array();
        $schedules = DB::SELECT("SELECT * FROM schedules AS SC WHERE SC.service_id = $service_id AND SC.available = 1;");
        $schedules = array_map(function ($value) { return (array)$value; }, $schedules);
        foreach ($data as $key => $value) {
            $data[$key]['age'] = date_diff(date_create($data[$key]['date_of_birth']), date_create('now'))->y;
            $data[$key]['ratings'] = $this->GetCleanerRatings($value['id']);
            $SchedCtr = 0;
            foreach ($schedules as $keys => $values) {
                if($data[$key]['id'] == $values['cleaner_id']) $SchedCtr++;
            }
            if($SchedCtr > 0) $out[] = $data[$key];
        }
    	return response()->json($out);
    }

    public function GetCleanerRatings($id){

        $rating = DB::SELECT("SELECT cs.rating FROM client_schedules as cs
            JOIN schedules ON cs.schedule_id = schedules.id
            WHERE cs.rating IS NOT NULL AND schedules.cleaner_id=$id;");

        return $this->FormatDBRatings($rating);
    }

    public function GetClientRatings($id){

        $rating = DB::SELECT("SELECT cs.rating_client FROM client_schedules as cs
            WHERE cs.rating_client IS NOT NULL AND cs.client_id=$id;");

        return $this->FormatDBRatings($rating);
    }

    private function FormatDBRatings($RatingsObject){
        $rate = array('star5' => 0, 'star4' => 0, 'star3' => 0, 'star2' => 0, 'star1' => 0, 'maxTotal' => 0, 'Total' => 0, 'rateCtr' => 0, 'rateAverage' => 0);
        foreach ($RatingsObject as $key => $value) {
            switch ($value->rating) {
                case 5:
                    $rate['star5'] += 1;
                    break;
                case 4:
                    $rate['star4'] += 1;
                    break;
                case 3:
                    $rate['star3'] += 1;
                    break;
                case 2:
                    $rate['star2'] += 1;
                    break;
                case 1:
                    $rate['star1'] += 1;
                    break;
                default:
                    break;
            }
            $rate['maxTotal'] += 5;
            $rate['Total'] += $value->rating;
            $rate['rateCtr'] += 1;
        }
            $rate['rateAverage'] = ($rate['Total'] <= 0) ? 0 : round(($rate['Total']/$rate['maxTotal'])*5, 1);

        return $rate;
    }


    public function cleaner_schedules($cleaner_id, $service_id){

    	$data = DB::table('schedules')
    	//->leftjoin('client_schedules', 'schedules.id', '=', 'client_schedules.schedule_id')
    	->leftjoin('days', 'days.id', '=', 'schedules.day_id')
        ->where('schedules.available','=' ,1)
    	->where('schedules.cleaner_id', $cleaner_id)
		->where('schedules.service_id', $service_id)
		->select('schedules.*', 'days.desc')
		->get();
		
		// var_dump($data);
		// exit;

    	$schedules = CleanerSchedule::collection($data);

    	return response()->json($schedules);
    }
}
