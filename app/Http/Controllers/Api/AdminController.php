<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User as Admin;
use Illuminate\Support\Facades\Hash;
use App\Models\ServiceProvider;
use App\Http\Resources\ServiceProvider as ServiceProviderResource;
use App\User;
use Auth;


class AdminController extends Controller
{
    public function index(){
        $admins = Admin::where('role_id', 1)->get();

        return response()->json($admins);
    }

    public function store(Request $request){
        $admin = Admin::firstOrCreate([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => 1,
        ]);

        $admin->profile()->create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'address' => $request->address,
            'mobile_number' => $request->mobile_number,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return response()->json();
    }

    public function update(Request $request, $id){
        $admin = Admin::findOrFail($id);
        $admin->update([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => 1,
        ]);

        $admin->profile()->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'address' => $request->address,
            'mobile_number' => $request->mobile_number,
            'date_of_birth' => $request->date_of_birth,
        ]);

        return response()->json();
    }

    public function destroy($id){

    }

    public function manage_accounts(){
        $users = User::all();

        return response()->json($users);
    }

    public function pending_requests(Request $request){
        if(!empty($request->input('action'))){
            $data = ServiceProvider::with('owner.profile')->where('approved', 0)->get();
            $data = $data->count();
            $data = ["total_count" => $data];
            return response()->json($data);
        }
        $data = ServiceProvider::with('owner.profile')->where('approved', 0)->get();
        $service_providers = ServiceProviderResource::collection($data);


        return response()->json($service_providers);
    }

    public function change_password(Request $request){
        $user = User::findOrFail(Auth::user()->id);
        $user->update([
            'password' => bcrypt($request->password),
        ]);

        return response()->json();
    }

    public function reports(){

    }
}
