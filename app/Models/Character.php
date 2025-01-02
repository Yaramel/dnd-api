<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    use HasFactory;
    protected $fillable = [
        'camp_id',
        'user_id',
        'race_id',
        'charclass_id',
        'name',
        'level',
        'atributes',
        'spell_list',
        'equipment_list',
        'description',
    ];
}
