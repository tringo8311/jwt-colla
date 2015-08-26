<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\UserObserver;
use App\UserNote;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        DB::table('users')->delete();

        $users = array(
            ['username' => 'Ryan Chenkie', 'first_name' => 'Ryan', 'last_name' => 'Chenkie', 'address' => '1026/1 Quang Trung, P.8 Go Vap', 'mobile' => '32131231234', 'zipcode' => '35622','email' => 'ryanchenkie@gmail.com', 'password' => Hash::make('secret'), 'role' => 'admin'],
            ['username' => 'Chris Sevilleja', 'first_name' => 'Chris', 'last_name' => 'Sevilleja', 'address' => '1026/1 Quang Trung, P.8 Go Vap', 'mobile' => '31231123412', 'zipcode' => '31321', 'email' => 'chris@scotch.io', 'password' => Hash::make('secret'), 'role' => 'customer'],
            ['username' => 'Holly Lloyd', 'first_name' => 'Holly', 'last_name' => 'Lloyd', 'address' => '1026/1 Quang Trung, P.8 Go Vap', 'mobile' => '412412512542', 'zipcode' => '31451', 'email' => 'holly@scotch.io', 'password' => Hash::make('secret'), 'role' => 'customer'],
            ['username' => 'Adnan Kukic', 'first_name' => 'Adnan', 'last_name' => 'Kukic', 'address' => '1026/1 Quang Trung, P.8 Go Vap', 'mobile' => '415412525123', 'zipcode' => '31231', 'email' => 'adnan@scotch.io', 'password' => Hash::make('secret'), 'role' => 'agency'],
        );

        // Loop through each user above and create the record for them in the database
        User::observe(new UserObserver());
        foreach ($users as $user){
            User::create($user);
        }

        $userNotes = array(
            ['user_id' => 2, 'barcode' => '321431312', 'product_code' => 'AB-123', 'content' => 'Chris'],
            ['user_id' => 2, 'barcode' => '321431312', 'product_code' => 'AB-123', 'content' => 'Holly'],
            ['user_id' => 1, 'barcode' => '321431312', 'product_code' => 'AB-123', 'content' => 'Ryan'],
            ['user_id' => 1, 'barcode' => '321431312', 'product_code' => 'AB-123', 'content' => 'Adnan'],
        );

        // Loop through each user above and create the record for them in the database
        UserNote::observe(new UserObserver());
        foreach ($userNotes as $user){
            UserNote::create($user);
        }

        $storeFeedback = array(
            ['user_id' => 1, 'service' => '321431312', 'employee' => 'AB-123', 'content' => 'Chris'],
            ['user_id' => 1, 'service' => '321431312', 'employee' => 'AB-123', 'content' => 'Chris'],
        );

        $storeOffer = array(
            ['user_id' => 1, 'store_id' => '128', 'subject' => 'AB-123', 'content' => 'Chris'],
            ['user_id' => 1, 'store_id' => '128', 'subject' => 'AB-123', 'content' => 'Chris'],
        );

        Model::reguard();
    }
}
