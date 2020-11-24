<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET foreign_key_checks = 0');
        DB::table('account')->truncate();
        DB::statement('SET foreign_key_checks = 1');

        $data = [[
            'user_name'=>'admin1',
            'user_pass'=>Hash::make('adminAbc'),
            'user_email'=>'admin1@kami.com',
            'user_status'=>1,
            'user_permission'=>1,

        ]];
        DB::table('account')->insert($data);
    }
}
