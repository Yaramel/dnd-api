<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'num_chars',
        'setting',
        'homebrew_list',
        'ban_list',
        'description',
    ];

    public function master()
    {
        return $this->belongsTo(Master::class);
    }
}
