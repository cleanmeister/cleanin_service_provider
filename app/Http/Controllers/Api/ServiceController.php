<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\Schedule;
use DB;
use Auth;
use App\User;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\ServiceSchedule as ServiceScheduleResource;
use App\Models\ServiceProvider;
use App\Models\CleanerServiceProvider;

class ServiceController extends Controller
{
    public function index(){
        $cleaner_id = Auth::user()->id;
        $cleaner_SP = CleanerServiceProvider::where('cleaner_id', $cleaner_id)->firstOrFail();

        $services = Service::where('name', 'like', '%'.request()->word.'%')
        ->where('desc', 'like', '%'.request()->word.'%')
        ->where('service_provider_id', $cleaner_SP["service_provider_id"])
        ->orderBy('name', 'asc')->get();

        return response()->json($services);
    }

    public function getServices(){

        $id = Auth::user()->id;
        //$data = ServiceProvider::with('services')->has('services')
        //->with('SPCleaners')->get();
        //$cleaner_SP = CleanerServiceProvider::where('cleaner_id', $cleaner_id)->firstOrFail();

        $cleaner_SP = DB::table('cleaner_service_provider')->where('cleaner_id', $id)->first();
        if($cleaner_SP == null){
            return response()->json(['message' => 'Forbidden. You are not allowed to view these page'], 422);
        }

        $services = Service::where('name', 'like', '%'.request()->word.'%')
        ->where('desc', 'like', '%'.request()->word.'%')
        ->where('service_provider_id', $cleaner_SP->service_provider_id)
        ->orderBy('name', 'asc')->get();/**/

        return response()->json($services);
    }

    public function store(Request $request){
        $sp = ServiceProvider::where('owner_id', Auth::user()->id)->first();

        $services = Service::create([
            'name' => $request->name,
            'desc' => $request->desc,
            'rate' => $request->rate,
            'price' => $request->price,
            'service_provider_id' => $sp->id,
        ]);

        return response()->json();
    }

    public function show($id){

        $service = Service::where('id', $id)->first();
        
        $cleaners = DB::SELECT("SELECT u.id, up.firstname, up.middlename,  up.lastname,  up.address,  up.mobile_number from users as u 
        left outer join schedules as s on s.cleaner_id = u.id 
        left outer join user_profiles as up on up.user_id = u.id 
        where s.service_id = $id
        group by u.id, up.firstname, up.middlename, up.lastname, up.address, up.mobile_number");


        return view('pages.service', compact('cleaners', 'service'));
    }

    public function select_cleaner($id){
        
        $user = User::where('id', $id)->first();


        
        return view('pages.service_user', compact('user'));
    }

    public function cleaner_service(){

        $cleaner_id = Auth::user()->id;
        $data = Service::whereHas('schedules', function($query) use ($cleaner_id){
            $query->where('cleaner_id', $cleaner_id)->orderBy('day_id', 'asc');
        })->get();
        $services = ServiceScheduleResource::collection($data);

        return response()->json($services);
    }

    public function cleaner_service_show($id){

    }

    public function update(Request $request, $id){
        $service = Service::findOrFail($id);
        $service->update([
            'name' => $request->name,
            'desc' => $request->desc,
            'rate' => $request->rate,
            'price' => $request->price,
        ]);
    }

    public function destroy($id){
        $service = Service::where('id', $id)->delete();

        return response()->json();
    }
}
