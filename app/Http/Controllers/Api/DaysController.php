<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Day;

class DaysController extends Controller
{
    public function index(){
        $days = Day::orderBy('id')->get();

        return response()->json($days);
    }
}
