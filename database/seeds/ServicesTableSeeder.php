<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Service;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->service_1();

    }
    public function service_1()
    {
        Service::create([
            'name' => 'General Cleaning',
            'desc' => 'general cleaning',
            'price' => 450,
            'rate' => 1,
            'service_provider_id' => 0
        ]);
    }


}
