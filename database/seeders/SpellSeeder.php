<?php

namespace Database\Seeders;

use App\Models\Spell;
use File;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SpellSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Load the JSON file
        $json = File::get(database_path('seeders/data/spells.json'));

        // Decode the JSON into an array
        $spells = json_decode($json, true);

        // Insert the spells into the table
        foreach ($spells as $spell) {
            // Encode the 'area' field as a JSON string
            $spell['area'] = json_encode($spell['area']);
            // Encode the 'components' field as a JSON string
            $spell['components'] = json_encode($spell['components']);
            // Encode the 'attack_save' field as a JSON string
            $spell['attack_save'] = json_encode($spell['attack_save']);
            Spell::create($spell);
        }
    }
}

