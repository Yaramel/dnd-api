<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharClass extends Model
{
    use HasFactory;
    protected $table = 'charclasses';
    protected $fillable = [
        'name',
        'hit_dice',
        'features_list',
        'spellcast_atr',
        'description',
    ];
}
