<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent; // Make sure this matches your Agent model path
use App\Models\Admin; 
use Illuminate\Support\Facades\Hash;

class AgentSeeder extends Seeder
{
    public function run(): void
    {
        Agent::create([
            'email'    => 'agent@gmail.com',
            'password' => Hash::make('password'), // Change this to a secure password
        ]);
    }
}