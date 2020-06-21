<?php

use Illuminate\Database\Seeder;
use App\Models\Day;

class DaysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->days();
    }

    public function days(){
        Day::create([
            'desc' => 'sunday',
            'initial' => 'M'
        ]);

        Day::create([
            'desc' => 'monday',
            'initial' => 'M'
        ]);

        Day::create([
            'desc' => 'tuesday',
            'initial' => 'T'
        ]);

        Day::create([
            'desc' => 'wednesday',
            'initial' => 'W'
        ]);

        Day::create([
            'desc' => 'thursday',
            'initial' => 'TH'
        ]);

        Day::create([
            'desc' => 'friday',
            'initial' => 'F'
        ]);

        Day::create([
            'desc' => 'saturdays',
            'initial' => 'F'
        ]);
    }
}
