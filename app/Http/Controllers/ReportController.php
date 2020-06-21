<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Exports\AdminExport;
use App\Models\ServiceProvider;
use App\User;
use App\Exports\CleanerClientScheduleExport;
use App\Exports\ClientExport;
use App\Exports\ServiceProviderExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\ClientSchedule as ClientScheduleResource;
use App\Http\Resources\CleanerClientSchedule as CleanerClientScheduleResource;
use App\Http\Resources\ServiceProviderBooking as ServiceProviderBookingResource;
use App\Models\ClientSchedule;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function cleaner_report(Request $request){
    	return Excel::download(new CleanerExport, 'cleaner_report.xlsx');
    }

    public function client_report(Request $request){
    	return Excel::download(new ClientExport, 'client_report.xlsx');
    }

    public function service_provider_report(Request $request){
    	return Excel::download(new ServiceProviderExport, 'service_provider_report.xlsx');
    }

    public function admin_report(Request $request){
    	return Excel::download(new AdminExport, 'admin_report.xlsx');
    }

    public function ccse(Request $request){
        return Excel::download(new CleanerClientScheduleExport, 'ccse.xlsx');
    }

    public function admin_create_report(Request $request){

        $status = (int)$request->status;
        $id = (int)Auth::user()->id;

        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

    	if($request->role_id == 2){
            $data = ServiceProvider::with('owner')
                ->whereHas('owner', function($q) use ($status){
                    $q->where('is_active', '=',$status);
                })->where(function($query) use ($from, $to){
                    $query->where('created_at', '>=', $from);
                    $query->where('created_at', '<=', $to);
                })
                ->get();
            
            return response()->json($data);
        }elseif ($request->role_id == 3) {
            $SPID = ServiceProvider::where('owner_id', Auth::user()->id)->first();

            $data = DB::table('users')
            ->leftjoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->leftjoin('cleaner_service_provider', 'cleaner_id', '=', 'users.id')
            ->where('cleaner_service_provider.service_provider_id', $SPID->id)
            ->where('users.role_id', 3)
            ->where('users.is_active', $status)
            ->where(function($query) use ($from, $to){
                $query->where('users.created_at', '>=', $from);
                $query->where('users.created_at', '<=', $to);
            })
            ->select(
                'users.id AS id',
                'users.is_active',
                'user_profiles.firstname',
                'user_profiles.lastname',
                'user_profiles.address',
                'user_profiles.mobile_number'
            )
            ->get();

            return response()->json($data);
        }elseif($request->role_id == 4){

            $SPID = ServiceProvider::where('owner_id', Auth::user()->id)->first();
            $IDs = DB::table('services')->where('services.service_provider_id', $SPID->id)
                ->leftjoin('schedules', 'schedules.service_id', '=', 'services.id')
                ->leftjoin('client_schedules', 'client_schedules.schedule_id', '=', 'schedules.id')
                ->where('client_schedules.client_id', '!=', null)
                ->groupBy('client_schedules.client_id')
                ->select("client_schedules.client_id")->get();
            //$tmp = array_map(function ($value) { return (array)$value; }, $IDs);

            $data = DB::table('users')
            ->leftjoin('user_profiles', 'user_profiles.user_id', '=', 'users.id')
            ->where('users.role_id', 4)
            ->where('users.is_active', $status)
            ->whereIn('users.id', json_decode(json_encode($IDs), true))
            ->where(function($query) use ($from, $to){
                $query->where('users.created_at', '>=', $from);
                $query->where('users.created_at', '<=', $to);
            })
            ->select(
                'users.id AS id',
                'users.is_active',
                'user_profiles.firstname',
                'user_profiles.lastname',
                'user_profiles.address',
                'user_profiles.mobile_number'
            )
            ->get();

            return response()->json($data);
        } elseif($request->role_id == 7) {
            $service_provider = ServiceProvider::where('owner_id', Auth::user()->id)->first();

            $data = DB::table('schedules as sc')
            ->leftjoin('client_schedules', 'client_schedules.schedule_id', '=', 'sc.id')
            ->leftjoin('services as s', 's.id', '=', 'sc.service_id')
            ->leftjoin('user_profiles as cleaner', 'cleaner.user_id', '=', 'sc.cleaner_id')
            ->leftjoin('user_profiles as client', 'client.user_id', '=', 'client_schedules.client_id')
            ->leftjoin('users AS clientUser', 'clientUser.id', '=', 'client.user_id')
            ->leftjoin('days as d', 'd.id', '=', 'sc.day_id')   
            ->where('s.service_provider_id', '=', $service_provider->id)
            ->where('client_schedules.status', '<>', null)
            ->where(function($query) use ($from, $to){
                $query->where('client_schedules.created_at', '>=', $from);
                $query->where('client_schedules.created_at', '<=', $to);
            })
            ->select('sc.service_id',
                's.name as service_name', 
                'cleaner.firstname as cleaner_firstname', 
                'cleaner.middlename as cleaner_middlename', 
                'cleaner.lastname as cleaner_lastname',
                'client.firstname as client_firstname',
                'client.middlename as client_middlename', 
                'client.lastname as client_lastname', 
                'clientUser.email as client_email',
                'sc.start_time', 
                'sc.end_time',
                'client_schedules.rating',
                'client_schedules.feedback',
                'client_schedules.id as client_schedule_id',
                'client_schedules.status',
                'client_schedules.time_in',
                'client_schedules.time_out',
                'client_schedules.sched_start_time',
                'client_schedules.sched_end_time',
                'd.desc as day_desc',
                'sc.day_id')->get();
            $service_provider_bookings = ServiceProviderBookingResource::collection($data);

            return response()->json($service_provider_bookings);
        }

        $status = (int)$request->status;
        $data =  User::with('profile')
            ->whereHas('profile',function($q) use ($status){
                $q->where('is_active', '=',$status);
            })->where('role_id', $request->role_id)->get();

    	return response()->json();
    }

    public function client_create_report(Request $request){
        $client_id = Auth::user()->id;
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

        $css = ClientSchedule::where('client_id', $client_id)
        ->whereBetween('created_at', [$from,$to])
        ->get();

        $data = ClientScheduleResource::collection($css);

        return response()->json($data);
    }

    public function cleaner_create_report(Request $request){
        $cleaner_id = Auth::user()->id;
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();

        $data = DB::table('schedules')
        ->join('days', 'days.id', '=', 'schedules.day_id')
        ->join('client_schedules', 'client_schedules.schedule_id', '=', 'schedules.id')
        ->join('users as client', 'client_schedules.client_id', '=', 'client.id')
        ->join('user_profiles as profile', 'profile.user_id', '=', 'client.id')
        ->join('services', 'services.id', '=', 'schedules.service_id')
        ->where('client_schedules.created_at', '>=', $from)
        ->where('client_schedules.created_at', '<=', $to)
        ->where('schedules.cleaner_id', $cleaner_id)->select(
            'schedules.id as schedule_id', 
            'client_schedules.id as id',
            'client_schedules.id as CSID',
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
            'client_schedules.status',
            'client_schedules.rating',
            'client_schedules.rating_client',
            'client_schedules.feedback',
            'client_schedules.time_in',
            'client_schedules.time_out',
            'client_schedules.landmark_address',
            'client_schedules.sched_start_time',
            'client_schedules.sched_end_time',
            'days.desc as day_desc'
        )->get();

        $schedules = CleanerClientScheduleResource::collection($data);

        return response()->json($schedules);
    }
}
