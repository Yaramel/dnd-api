<?php

namespace Database\Seeders;

use App\Models\Equipment;
use File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load the JSON file
        $json = File::get(database_path('seeders/data/equipments.json'));

        // Decode the JSON into an array
        $equipments = json_decode($json, true);

        // Insert the equipments into the table
        foreach ($equipments as $equipment) {
            // Encode the 'info' field as a JSON string
            $equipment['info'] = json_encode($equipment['info']);
            Equipment::create($equipment);
        }
    }
}
