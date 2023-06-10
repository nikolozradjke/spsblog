<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin_count = User::where('email', 'admin@dga.gov.ge')->exists();

        if(!$admin_count)
        User::create([
            'name' => 'ადმინისტრატორი',
            'email' => 'admin@dga.gov.ge',
            'password' => Hash::make('Admini!@#'),
            'role' => 1
        ]);
    }
}
