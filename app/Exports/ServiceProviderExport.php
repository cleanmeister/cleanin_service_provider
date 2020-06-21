<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\User;
use DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ServiceProviderExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = User::where('role_id', 2)->get();

        return $data;
    }

    public function view(): View
    {
    	$service_providers = User::where('role_id', 2)->get();

    	return view('reports.service_provider', compact('service_providers'));
    }
}
