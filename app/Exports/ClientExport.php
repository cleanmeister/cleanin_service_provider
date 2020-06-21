<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\User;
use DB;

class ClientExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = User::where('role_id', 4)->get();
        return $data;
    }

    public function view(): View
    {
    	$clients = User::where('role_id', 4)->get();

    	return view('reports.client', compact('clients'));
    }
}
