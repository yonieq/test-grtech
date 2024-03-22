<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $faker = Faker::create();

        foreach (range(1, 100) as $index) {
            Employee::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'company_id' => random_int(1, 100),
                'email' => $faker->email(),
                'phone' => $faker->phoneNumber()
            ]);
        }
    }
}
