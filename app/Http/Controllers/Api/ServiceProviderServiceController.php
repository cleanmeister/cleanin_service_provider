<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Models\ServiceProvider;
use App\Models\Service;
use App\Models\ClientSchedule;

class ServiceProviderServiceController extends Controller
{
    public function index(){
    	$owner_id = Auth::user()->id;
        $word = request()->word;
    	$sp = ServiceProvider::with(['services' => function($query) use ($word) {
            $query->where('name', 'like', '%'.$word.'%')->orWhere('desc', 'like', '%'.$word.'%');
        },])->where('owner_id', $owner_id)->first();
    	$services = $sp->services;

    	return response()->json($services);
    }

    public function store(Request $request){

        $owner_id = Auth::user()->id;

        $validator = Validator::make($request->all(), [
            'name' => [
                'required', function ($attribute, $value, $fail) use ($owner_id){
                    $service = ServiceProvider::wherehas('services', function($query) use ($value){
                        $query->where('name', $value);
                    })->where('owner_id', $owner_id)->first();

                    //if (Service::where('name', $value)->first() !== null) {
                    if($service !== null){
                        $fail('<h5><b class="text text-danger">Service Name</b> already exists!</h5>');
                    }
                }
            ]
        ]);

        $request->validate([
            'name' => 'required:max:255',
            'desc' => 'required:max:255',
            'rate' => 'required',
            'price' => 'required'
        ], [
            'desc.required' => '<h5>The <b class="text-danger">Description</b> field is required.</h5>'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => "The given data was invalid.", 'errors' => $validator->messages()], 422);
        }

    	$sp = ServiceProvider::where('owner_id', $owner_id)->first();

    	$service = Service::firstOrCreate([
    		'name' => $request->name,
    		'service_provider_id' => $sp->id,
    	], [
    		'desc'=> $request->desc,
	    	'rate'=> $request->rate,
	        'price'=> $request->price,
    	]);

        return response()->json(["msg" => "New service added successfully!", "code" => 200]);
    }

    public function update(Request $request, $id){

        $owner_id = Auth::user()->id;
        $service = Service::findOrFail($id);

        /**/
        $validator = Validator::make($request->all(), [
            'name' => [
                'required', function ($attribute, $value, $fail) use ($id) {
                    if (Service::where('name', $value)->where('id', '!=', $id)->first() !== null) {
                        $fail('<h5><b class="text text-danger">Service Name</b> already exists!</h5>');
                    }
                }
            ]
        ]);

        $request->validate([
            'name' => 'required:max:255',
            'desc' => 'required:max:255',
            'rate' => 'required',
            'price' => 'required'
        ], [
            'desc.required' => '<h5>The <b class="text-danger">Description</b> field is required.</h5>'
        ]);

        if($validator->fails()) {
            return response()->json(['message' => "The given data was invalid.", 'errors' => $validator->messages()], 422);
        }

    	$service->update([
    		'name' => $request->name,
    		'desc'=> $request->desc,
	    	'rate'=> $request->rate,
	        'price'=> $request->price,
    	]);

        return response()->json(["msg" => "Update successful!", "code" => 200]);
    }

    public function destroy($id){
    	//$servie = Service::where('id', $id)->delete();
        $cs = ClientSchedule::leftjoin('schedules', 'schedules.id', '=', 'client_schedules.schedule_id')
            ->leftjoin('services', 'services.id', '=', 'schedules.service_id')
            ->where('services.id', '=', $id)
            ->wherein('client_schedules.status', [1,2])->get();

        if($cs->count() > 0){
            return response()->json(['msg' => 'Unable to disable service, there are ongoing transactions.'], 422);
        }
        Service::where('id', $id)->update(['status' => 0]);
    	return response()->json(['message' => 'Service Successfully Disabled.']);
    }

    public function enableService($id){
        $stat = Service::where('id', $id)->update(['status' => 1]);
        if(!$stat) return response()->json(['msg' => 'Failed to activate selected service!'], 422);
        return response()->json(['message' => 'Service Successfully Enabled.']);
    }
}
