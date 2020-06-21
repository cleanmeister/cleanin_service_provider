<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\MessageContacts;
use Auth;
use App\User;
use DB;
use App\Http\Resources\Messages as MessageResource;

class MessageController extends Controller
{

    public function __construct(){
       
    }
    public function index(){

    }

    public function contanct_message($id){
        $sender_id = Auth::user()->id;
        $receiver_id = $id;

        $messages = Message::where('sender_id', $sender_id)->where('receiver_id', $receiver_id)->get();
        
        return response()->json($messages);
    }


    public function get_message($id){
        $sender_id = Auth::user()->id;
        $receiver_id = $id;

        Message::where("sender_id", '=', $receiver_id)
            ->where('receiver_id', '=', $sender_id)
            ->where('status', '=', 0)
            ->update([
                'status' => 1
            ]);

        // $messages = DB::select("SELECT 
        //     m.sender_id, 
        //     m.receiver_id, 
        //     m.text_message,
        //     m.created_at,
        //     m.id as count,
        //     sp.firstname as sender_firstname,
        //     sp.middlename as sender_middlename,
        //     sp.lastname as sender_lastname,
        //     sp.profile_pic as sender_profile_pic,
        //     rp.firstname as receiver_firstname,
        //     rp.middlename as receiver_middlename,
        //     rp.lastname as receiver_lastname
        // from messages as m 
        // left outer join users as s on s.id = m.sender_id
        // left outer join users as r on r.id = m.receiver_id
        // left outer join user_profiles as sp on sp.user_id = s.id
        // left outer join user_profiles as rp on rp.user_id = r.id
        // where (m.sender_id = $sender_id 
        // and m.receiver_id = $receiver_id) 
        // OR (m.receiver_id = $sender_id 
        // and m.sender_id = $receiver_id) 
        // order by m.created_at desc
        // limit 5 ");
        $messages = Message::with('sender','receiver')
            ->where(function($query) use ($sender_id, $receiver_id){
                $query->where(function($querys) use ($sender_id, $receiver_id){
                    $querys->where('sender_id', '=', $sender_id);
                    $querys->where('receiver_id', '=', $receiver_id);
                });
                $query->orwhere(function($querys) use ($sender_id, $receiver_id){
                    $querys->where('sender_id', '=', $receiver_id);
                    $querys->where('receiver_id', '=', $sender_id);
                });

            })
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
        $res = MessageResource::collection($messages);

        return response()->json($res);
    }

    function get_prev_message($id, $ctr)
    {
        $sender_id = Auth::user()->id;
        $receiver_id = $id;

        // $messages = DB::select("SELECT 
        //     m.sender_id, 
        //     m.receiver_id, 
        //     m.text_message,
        //     m.created_at,
        //     m.id as count,
        //     sp.firstname as sender_firstname,
        //     sp.middlename as sender_middlename,
        //     sp.lastname as sender_lastname,
        //     sp.profile_pic as sender_profile_pic,
        //     rp.firstname as receiver_firstname,
        //     rp.middlename as receiver_middlename,
        //     rp.lastname as receiver_lastname
        // from messages as m 
        // left outer join users as s on s.id = m.sender_id
        // left outer join users as r on r.id = m.receiver_id
        // left outer join user_profiles as sp on sp.user_id = s.id
        // left outer join user_profiles as rp on rp.user_id = r.id
        // where (m.sender_id = $sender_id 
        // and m.receiver_id = $receiver_id) 
        // OR (m.receiver_id = $sender_id 
        // and m.sender_id = $receiver_id) 
        // order by m.created_at desc
        // limit ".$ctr.", 5");

        $messages = Message::with('sender','receiver')
            ->where(function($query) use ($sender_id, $receiver_id){
                $query->where(function($querys) use ($sender_id, $receiver_id){
                    $querys->where('sender_id', '=', $sender_id);
                    $querys->where('receiver_id', '=', $receiver_id);
                });
                $query->orwhere(function($querys) use ($sender_id, $receiver_id){
                    $querys->where('sender_id', '=', $receiver_id);
                    $querys->where('receiver_id', '=', $sender_id);
                });

            })
            ->orderBy('created_at', 'DESC')
            ->offset($ctr)
            ->limit(5)
            ->get();
        $res = MessageResource::collection($messages);

        return response()->json($res);
    }

    function get_new_message($id){
        $sender_id = Auth::user()->id;
        $receiver_id = $id;

        $messages = Message::with('sender','receiver')
            ->where('status', '=', 0)
            ->where(function($query) use ($sender_id, $receiver_id){
                $query->where(function($querys) use ($sender_id, $receiver_id){
                    $querys->where('sender_id', '=', $sender_id);
                    $querys->where('receiver_id', '=', $receiver_id);
                });
                $query->orwhere(function($querys) use ($sender_id, $receiver_id){
                    $querys->where('sender_id', '=', $receiver_id);
                    $querys->where('receiver_id', '=', $sender_id);
                });

            })
            ->orderBy('created_at', 'DESC')
            ->get();

        if($messages->count() > 0){
            Message::where("sender_id", '=', $receiver_id)
                ->where('receiver_id', '=', $sender_id)
                ->update([
                    'status' => 1
                ]);
            if($messages->first()->receiver_id == $sender_id){
                $res = MessageResource::collection($messages);
                return response()->json(MessageResource::collection($res));
            }
        }
        return response()->json();
    }


    public function send_message(Request $request, $id){
        $sender_id = Auth::user()->id;
        $receiver_id = $id;

        $contact = MessageContacts::firstOrCreate([
                'user_id' => $sender_id,
                'contact_id' => $receiver_id
            ],[
                'user_id' => $sender_id,
                'contact_id' => $receiver_id
            ]);

        $contactee = MessageContacts::firstOrCreate([
                'user_id' => $receiver_id,
                'contact_id' => $sender_id
            ],[
                'user_id' => $receiver_id,
                'contact_id' => $sender_id
            ]);

        $message = Message::create([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'text_message' => $request->text_message,
            'status' => 0,
        ]);

        return response()->json(['time' => $message->created_at]);
    }

    public function view_message($sender_id){
        $messages = Message::where('sender_id', $sender_id)->update([
            'status' => 1
        ]);

        return response()->json();
    }

    public function get_user_id(){
        $user = User::with('profile')
            ->where('id', Auth::user()->id)
            ->first();

        $out = array(
            'id' => $user->id,
            'profile_pic' => $user->profile->profile_pic
        );

        return response()->json($out);
    }

    public function new_user_messages(){
        $user_id = Auth::user()->id;
        $messages = Message::where('receiver_id', $user_id)
            ->where('status','=', 0)->get();
        return response()->json($messages->count());
    }

    public function user_and_messages(){
        
    }

}
