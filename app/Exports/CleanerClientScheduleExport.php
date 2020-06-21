<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CleanerClientScheduleExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view(): View
    {
    	$data = DB::table('client_schedules as cs')
		    	->leftjoin('schedules as s', 'cs.schedule_id', '=', 's.id')
		    	->leftjoin('user_profiles as cleaner', 'cleaner.user_id', '=', 's.cleaner_id')
		    	->leftjoin('user_profiles as client', 'client.user_id', '=', 'cs.client_id')
		    	->leftjoin('days as d', 'd.id', '=', 's.day_id')
		    	->leftjoin('services as ser', 'ser.id', '=', 's.service_id')
		    	->where('cs.id', '<>', null)
		    	->select('cleaner.firstname as cleaner_firstname', 'cleaner.middlename as cleaner_middlename',
		    			'cleaner.lastname as cleaner_lastname', 'client.firstname as client_firstname',
		    			'client.middlename as client_middlename', 'client.lastname as client_lastname',
		    			'd.desc as day_desc', 's.start_time', 's.end_time', 'cs.rating', 'cs.status',
		    			'cs.time_in', 'cs.time_out', 'ser.name as service_name'
		    	)->get();

    	return view('reports.ccse', compact('data'));
    }


}
