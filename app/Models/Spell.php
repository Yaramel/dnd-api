<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spell extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'level',
        'casting_time',
        'range',
        'area',
        'components',
        'duration',
        'school',
        'attack_save',
        'description',
        'damage_effect',
        'damage',
    ];

    public function master()
    {
        return $this->belongsTo(Master::class);
    }

    public function classes()
    {
        return $this->belongsToMany(CharClass::class, 'casts', 'id', 'id');
    }
}
