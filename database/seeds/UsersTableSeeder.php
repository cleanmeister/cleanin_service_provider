<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersTableSeeder extends Seeder
{

    public function run()
    {
        $admin = User::create([
            'email' => 'admin@email.com',
            'username' => 'admin',
            'password' => bcrypt(1),
            'role_id' => 1,
        ]);

        $admin->profile()->create([
            'firstname' => 'admin firstname',
            'lastname' => 'admin lastname',
            'middlename' => 'admin middlename',
            'address' => 'admin address',
            'mobile_number' => 'admin number',
        ]);

        $owner = User::create([
            'email' => 'owner@email.com',
            'username' => 'owner',
            'password' => bcrypt(1),
            'role_id' => 2,
        ]);

        $owner->profile()->create([
            'firstname' => 'owner firstname',
            'lastname' => 'owner lastname',
            'middlename' => 'owner middlename',
            'address' => 'owner address',
            'mobile_number' => 'owner number',
        ]);

        $cleaner = User::create([
            'email' => 'cleaner@email.com',
            'username' => 'cleaner',
            'password' => bcrypt(1),
            'role_id' => 3,
        ]);

        $cleaner->profile()->create([
            'firstname' => 'cleaner firstname',
            'lastname' => 'cleaner lastname',
            'middlename' => 'cleaner middlename',
            'address' => 'cleaner address',
            'mobile_number' => 'cleaner number',
        ]);

        $clients = User::create([
            'email' => 'clients@email.com',
            'username' => 'clients',
            'password' => bcrypt(1),
            'role_id' => 4,
        ]);

        $clients->profile()->create([
            'firstname' => 'clients firstname',
            'lastname' => 'clients lastname',
            'middlename' => 'clients middlename',
            'address' => 'clients address',
            'mobile_number' => 'clients number',
        ]);

        $justin = User::create([
            'email' => 'justin@email.com',
            'username' => 'justin',
            'password' => bcrypt('justin'),
            'role_id' => 3,
        ]);

        $justin->profile()->create([
            'firstname' => 'justin ',
            'lastname' => 'perez',
            'middlename' => 'g',
            'address' => 'baguio',
            'mobile_number' => '09472765053',
        ]);
    }
    
    
}
