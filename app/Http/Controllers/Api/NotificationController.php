<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\Models\Notification;
use App\Http\Resources\Notification as NotificationResource;

class NotificationController extends Controller
{
    public function index(){
        $notifications = Notification::all();

        return response()->json($notifications);
    }

    public function store(Request $request){
        $notification = Notification::firstOrCreate($request->all());

        return response()->json();
    }

    public function update(Request $request, $id){
        $notification = Notification::findOrFail($id);
        $notification->update($request->all());
        return response()->json();
    }

    public function destroy(){

    }
    
    public function get_user_notifs(){
        $user_id = Auth::user()->id;
        $data = Notification::where('receiver_id', $user_id)
        ->orderBy('id', 'desc')
        ->get();

        $notifs = NotificationResource::collection($data);

        return response()->json($notifs);
    }

    public function new_user_notifs(){
        $user_id = Auth::user()->id;
        $notifs = Notification::where('receiver_id', $user_id)
        ->where('viewed',0)
        ->orderBy('created_at', 'desc')
        ->count();
     

        return response()->json($notifs);
    }

    public function view($id){

        $notif = Notification::findOrFail($id);
        $notif->update([
            'viewed' => 1
        ]);

        return response()->json();
    }

    public function view_all(){
        $user_id = Auth::user()->id;
        $notifs = Notifaction::where('user_id', $user_id)->update([
            'viewed' => 1
        ]);

        return response()->json();
    }
}
