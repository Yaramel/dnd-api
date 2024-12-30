<?php

namespace Database\Seeders;

use App\Models\Race;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $races = [
            [
                "name" => "Dragonborn",
                "speed" => "30",
                "description" => "Proud and strong, Dragonborn are humanoid dragons with powerful abilities tied to their draconic heritage.",
            ],
            [
                "name" => "Dwarf",
                "speed" => "25",
                "description" => "Stout and hardy, Dwarves are known for their toughness and affinity for crafting and mining.",
            ],
            [
                "name" => "Elf",
                "speed" => "30",
                "description" => "Graceful and timeless, Elves possess keen senses and a strong connection to magic.",
            ],
            [
                "name" => "Gnome",
                "speed" => "30",
                "description" => "Inventive and curious, Gnomes have a knack for mechanical wonders and magical studies.",
            ],
            [
                "name" => "Half-Elf",
                "speed" => "30",
                "description" => "Versatile and charming, Half-Elves blend the traits of humans and elves, excelling in adaptability.",
            ],
            [
                "name" => "Half-Orc",
                "speed" => "30",
                "description" => "Strong and resilient, Half-Orcs combine human determination with orcish ferocity.",
            ],
            [
                "name" => "Halfling",
                "speed" => "30",
                "description" => "Small but courageous, Halflings are known for their luck and ability to stay out of trouble.",
            ],
            [
                "name" => "Human",
                "speed" => "30",
                "description" => "Ambitious and diverse, Humans are adaptable and excel in a wide variety of fields.",
            ],
            [
                "name" => "Tiefling",
                "speed" => "30",
                "description" => "Born of infernal bloodlines, Tieflings are often misunderstood but possess innate magical abilities.",
            ],
        ];

        foreach ($races as $race) {
            Race::create($race);
        }
    }
}
