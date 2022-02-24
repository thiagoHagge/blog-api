<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Admin::create([
            'usr_name' => env('USR_NAME', false),
            'usr_pass' => Hash::make(env('USR_PASS', false)),
            'usr_token' => '6216b3b28d8f8'
        ]);
    }
}
