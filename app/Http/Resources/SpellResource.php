<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SpellResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>     
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'level' => $this->level,
            'casting_time' => $this->casting_time,
            'range' => $this->range,
            'area' => $this->area,
            'components' => $this->components,
            'duration' => $this->duration,
            'school' => $this->school,
            'attack_save' => $this->attack_save,
            'description' => $this->description,
            'damage_effect' => $this->damage_effect,
            'damage' => $this->damage,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}