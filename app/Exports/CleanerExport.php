<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CleanerExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = User::where('role_id', 3)->get();

        return $data;
    }

    public function view(): View
    {
    	$cleaners = User::where('role_id', 4)->get();

    	return view('reports.cleaner', compact('cleaners'));
    }

}
