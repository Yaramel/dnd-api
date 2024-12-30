<?php

namespace Database\Seeders;

use App\Models\Cast;
use File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CastSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load the JSON file
        $json = File::get(database_path('seeders/data/casts.json'));

        // Decode the JSON into an array
        $casts = json_decode($json, true);

        // Insert the casts into the table
        foreach ($casts as $cast) {
            Cast::create($cast);
        }
    }
}
