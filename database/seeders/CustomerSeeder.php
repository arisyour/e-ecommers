<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Customer::create([
            'name' => 'Admin',
            'email' => 'admin@ifump.net',
            'password' => Hash::make('password'),
            'phone' => '08124554637',
            'address' => 'klskjhejtrterfdgdgdgdfgdg'
        ]);
    }
}