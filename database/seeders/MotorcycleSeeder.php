<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Motorcycle;

class MotorcycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $motorcycles = [
            [
                'name' => 'Honda Click 125i',
                'transmission' => 'Automatic',
                'engine_cc' => 125,
                'daily_rate' => 600,
                'is_available' => true,
            ],
            [
                'name' => 'Yamaha Mio i125',
                'transmission' => 'Automatic',
                'engine_cc' => 125,
                'daily_rate' => 500,
                'is_available' => true,
            ],
            [
                'name' => 'Yamaha NMAX 155',
                'transmission' => 'Automatic',
                'engine_cc' => 155,
                'daily_rate' => 800,
                'is_available' => true,
            ],
            [
                'name' => 'Honda XR150L',
                'transmission' => 'Manual',
                'engine_cc' => 150,
                'daily_rate' => 800,
                'is_available' => true,
            ],
            [
                'name' => 'Suzuki V-Strom 160',
                'transmission' => 'Manual',
                'engine_cc' => 160,
                'daily_rate' => 850,
                'is_available' => true,
            ],
        ];

        foreach ($motorcycles as $motorcycle) {
            Motorcycle::firstOrCreate(
                ['name' => $motorcycle['name']],
                $motorcycle
            );
        }
    }
}