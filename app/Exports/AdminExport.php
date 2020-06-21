<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class AdminExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = User::where('role_id', 1)->get();

        return $data;
    }

    public function view(): View
    {
    	$admins = User::where('role_id', 1)->get();

    	return view('reports.admin', compact('admins'));
    }
}
