<?php

namespace Database\Seeders;

use App\Models\CharClass;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CharClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = [
            [
                "name" => "Barbarian",
                "hit_dice" => 12,
                "spellcast_atr" => "N/A",
                "features_list" => json_encode(["Rage", "Unarmored Defense", "Reckless Attack"]),
                "description" => "Fierce warriors of primitive background who can enter a battle rage.",
            ],
            [
                "name" => "Bard",
                "hit_dice" => 8,
                "spellcast_atr" => "cha",
                "features_list" => json_encode(["Spellcasting", "Bardic Inspiration", "Jack of All Trades"]),
                "description" => "Charismatic performers whose magic comes from the music they weave.",
            ],
            [
                "name" => "Cleric",
                "hit_dice" => 8,
                "spellcast_atr" => "wis",
                "features_list" => json_encode(["Spellcasting", "Divine Domain", "Turn Undead", "Channel Divinity"]),
                "description" => "Servants of divine entities who channel their powers to heal or smite.",
            ],
            [
                "name" => "Druid",
                "hit_dice" => 8,
                "spellcast_atr" => "wis",
                "features_list" => json_encode(["Wild Shape", "Spellcasting", "Druidic"]),
                "description" => "Nature priests who can shapeshift into animals and wield elemental magic.",
            ],
            [
                "name" => "Fighter",
                "hit_dice" => 10,
                "spellcast_atr" => "N/A",
                "features_list" => json_encode(["Second Wind", "Action Surge", "Combat Style"]),
                "description" => "Masters of martial combat, skilled with a variety of weapons and armor.",
            ],
            [
                "name" => "Monk",
                "hit_dice" => 8,
                "spellcast_atr" => "N/A",
                "features_list" => json_encode(["Unarmored Defense", "Martial Arts", "Ki"]),
                "description" => "Warriors who train their bodies to harness mystical energy known as ki.",
            ],
            [
                "name" => "Paladin",
                "hit_dice" => 10,
                "spellcast_atr" => "cha",
                "features_list" => json_encode(["Spellcasting", "Divine Smite", "Lay on Hands", "Sacred Oath"]),
                "description" => "Holy warriors bound by a sacred oath to protect the innocent and smite evil.",
            ],
            [
                "name" => "Ranger",
                "hit_dice" => 10,
                "spellcast_atr" => "wis",
                "features_list" => json_encode(["Favored Enemy", "Natural Explorer", "Spellcasting"]),
                "description" => "Skilled hunters who use magic to track foes and survive in the wilds.",
            ],
            [
                "name" => "Rogue",
                "hit_dice" => 8,
                "spellcast_atr" => "N/A",
                "features_list" => json_encode(["Sneak Attack", "Cunning Action", "Evasion"]),
                "description" => "Masters of stealth and precision strikes, excelling in ambush tactics.",
            ],
            [
                "name" => "Sorcerer",
                "hit_dice" => 6,
                "spellcast_atr" => "cha",
                "features_list" => json_encode(["Spellcasting", "Sorcerous Origin", "Metamagic"]),
                "description" => "Wielders of innate magic, often derived from a powerful magical lineage.",
            ],
            [
                "name" => "Warlock",
                "hit_dice" => 8,
                "spellcast_atr" => "cha",
                "features_list" => json_encode(["Spellcasting", "Pact Magic", "Eldritch Invocations", "Otherworldly Patron"]),
                "description" => "Casters who derive power from a pact with an otherworldly being.",
            ],
            [
                "name" => "Wizard",
                "hit_dice" => 6,
                "spellcast_atr" => "int",
                "features_list" => json_encode(["Spellbook", "Arcane Recovery", "Spellcasting"]),
                "description" => "Scholars of arcane magic who meticulously study and wield its power.",
            ],
        ];

        foreach ($classes as $class) {
            CharClass::create($class);
        }
    }
}
