<?php 
namespace App\CustomHelpers;

//header('Content-type:application/json;charset=utf-8');
use Illuminate\Http\Request;
use DateTime;

class ScheduleClass{
    
 
    public static function validateSchedule($start_time_db,$end_time_db,$start_time_timestamp,$end_time_timestamp){
        //$msg = array();

        //$days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
        //$start_time_format = date('h:i a',$start_time_timestamp);
        //$end_time_format = date('h:i a', $end_time_timestamp);

        $from = $start_time_db;//new DateTime(date('h:i a', ));
        $to = $end_time_db;//new DateTime(date('h:i a', ));
        $fromCompare = $start_time_timestamp;//new DateTime(date('h:i a', ));
        $toCompare = $end_time_timestamp;//new DateTime(date('h:i a', ));
    
        if(($from == $fromCompare && $to == $toCompare)){
            return true;
            //array(
            //    "msg" => 'Existing Schedule for : ' . $schedule["service"]["name"] . '  On ' .  $days[$day - 1] . '<br> Start Time: '.     $start_time_format   . '<br> End Time :' .  $end_time_format,
            //    "code" => 500
            //);
        }
        if(ScheduleClass::dateIsBetween($from, $to, $fromCompare, $toCompare)){
            return true;
            //array(
            //    "msg" => 'Overlapping Schedule for : ' . $schedule["service"]["name"] . '  On ' .  $days[$day - 1] . '<br> Start Time: '.  $start_time_format  . '<br> End Time :' .$end_time_format,
            //    "code" => 500
            //);
        }
        return false;
    }
    /*public static function dateIsBetween($dbfrom1,$dbto1,$user_st,$user_et) {
        //return ((($dbfrom1 >= $user_st) and ($dbfrom1 <= $user_et)) or ($dbto1 >= $user_st) and ($dbto1 <= $user_et));
        return ((($dbfrom1 >= $user_st) && ($dbfrom1 <= $user_et)) || ($dbto1 >= $user_st) && ($dbto1 <= $user_et));
    }*/
    public static function dateIsBetween($dbFrom,$dbTo,$UserFrom,$UserTo){
        //this is logic1 ((StartA <= EndB) and (EndA >= StartB))
        return (($dbFrom <= $UserTo) && ($dbTo >= $UserFrom));
        //this is logic2 (StartA > StartB? Start A: StartB) <= (EndA < EndB? EndA: EndB)
        //return ($dbFrom > $UserFrom ? $dbFrom: $UserFrom) <= ($dbTo < $UserTo ? $dbTo: $UserTo);
    }

}