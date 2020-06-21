<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User as Cleaner;
use App\Http\Resources\User as CleanerResource;
use Illuminate\Support\Facades\Hash;
use App\Models\ServiceProvider;
use App\Models\Schedule;
use App\Models\CleanerServiceProvider;
use Auth;
use App\Http\Resources\CleanerServiceProvider as CleanerServiceProviderResource;
use DB;
use Validator;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class CleanerController extends Controller
{
    public function index(){
        if(!Auth::check() || Auth::user()->role_id != 2 ){
            throw ValidationException::withMessages(['msg' => 'invalid login!']);
        }
        $sp = ServiceProvider::where('owner_id', Auth::user()->id)->first();
        $word = request()->word;
        // var_dump($sp);

        $data = CleanerServiceProvider::with(['cleaner', 'service_provider'])
            ->whereHas('cleaner.profile',function($query) use ($word){
                $query->where('firstname', 'like', '%'.$word.'%')
                ->orWhere('middlename', 'like', '%'.$word.'%')
                ->orWhere('lastname', 'like', '%'.$word.'%');
                $query->where('is_active', '=', 1);
            })
            ->where('service_provider_id', $sp->id)
            ->where('status', '=', 1)
            ->get();

        // echo '<pre>';
        // print_r($data);
        // echo '<pre>';
        /*$data = DB::table('user_profiles as u')
        ->join('cleaner_service_provider as csp', 'csp.cleaner_id', '=', 'u.id')
        ->where('csp.service_provider_id', $sp->id)
        ->select('u.user_id as id', 'u.firstname', 'u.middlename', 'u.lastname')
        ->get();*/

        $cleaners = CleanerServiceProviderResource::collection($data);

        return response()->json($cleaners);
    }

    public function store(Request $request){

        $request->validate([
            'username' => 'required|max:255|unique:users,username',
            'password' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'middlename' => 'required|max:255',
            'date_of_birth' => 'required|max:255',
            'address' => 'required|max:255',
            'mobile_number' => 'required|max:255',
        ]);

        $sp = ServiceProvider::where('owner_id', Auth::user()->id)->first();

        $cleaner = Cleaner::firstOrCreate([
            'email' => $request->email,
            'email_verified_at' => Carbon::now()->timestamp,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => 3,
        ]);

        $cleaner->profile()->create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'address' => $request->address,
            'mobile_number' => $request->mobile_number,
            'date_of_birth' => Carbon::parse($request->date_of_birth)->toDateTimeString()
        ]);

        $csp = CleanerServiceProvider::firstOrCreate([
            'cleaner_id' => $cleaner->id,
            'service_provider_id' => $sp->id,
            'cleaner_restrictions' => $request->restrictions,
        ]);

        return response()->json(["msg" => "New Cleaner successfully added!", "code" => 200]);
    }

    public function update(Request $request, $id){
        $cleaner = Cleaner::findOrFail($id);

        $request->validate([
            'username' => 'required|max:255|unique:users,username',
            'password' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'middlename' => 'required|max:255',
            'date_of_birth' => 'required|max:255',
            'address' => 'required|max:255',
            'mobile_number' => 'required|max:255',
        ]);

        $cleaner->update([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role_id' => 3,
        ]);

        $cleaner->profile()->update([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'middlename' => $request->middlename,
            'address' => $request->address,
            'mobile_number' => $request->mobile_number,
            'date_of_birth' => $request->date_of_birth,
        ]);
        $scp = CleanerServiceProvider::where('cleaner_id','=', $id)->first()
            ->update([
                'cleaner_restrictions' => $request->restrictions,
            ]);

        return response()->json(["msg" => "Update successful!", "code" => 200]);
    }

    public function destroy($id){
        if(!ctype_digit($id) || $id == '' || $id == null){
            throw ValidationException::withMessages(['Invalid Request!']);
        }
        $sp = ServiceProvider::where('owner_id', Auth::user()->id)->first();
        $spID = $sp->id;

        $cleanerActiveTransactions = Schedule::whereHas('cleaner', function($query) use($spID, $id){
                $query->where('users.id', '=', $id)->wherehas('cleaner_service_provider', function($querys) use ($spID){
                    $querys->where('cleaner_service_provider.service_provider_id', '=', $spID);
                });
            })
            ->where('available', '=', 0)->get();

        if($cleanerActiveTransactions->count() > 0){
            return response()->json(["message" => "Cannot terminate cleaner.", "errors" => array("terminate" => ["Cleaner have pending transactions!"])], 422);
        }
            //throw ValidationException::withMessages(['Invalid Request!']);
        $cleaner = CleanerServiceProvider::where('cleaner_id', $id)->update([
            'status' => 0
        ]);
        $cleaner = Cleaner::findOrFail($id)->update(['is_active' => 0]);
        return response()->json(["msg" => "Update successful!", "code" => 200]);
        //return response()->json($cleanerActiveTransactions);
    }

    public function cleaner_service_provider($id){
        $data = DB::SELECT();
    }
}
