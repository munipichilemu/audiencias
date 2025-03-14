<?php

namespace Database\Seeders;

use App\Models\Beneficiary;
use App\Models\Hearing;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Alejandro Sanhueza Morales',
            'email' => 'dev@jano.cl',
            'password' => Hash::make('1234'),
            'remember_token' => 'H6MG9nGnH0PVPsKiuWDBSNBjFKcbMTfDpmKYEiw7yWDfTqgNTD6bYx33ymLt',
        ]);

        $this->call([
            SectorsSeeder::class,
            RequestTypeSeeder::class,
        ]);

        Beneficiary::factory()->count(50)->create();
        Hearing::factory()->count(100)->create();
    }
}
