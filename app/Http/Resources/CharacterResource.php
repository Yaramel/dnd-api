<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CharacterResource extends JsonResource
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
            'camp_id'=> $this->camp_id,
            'user_id'=> $this->player_id,
            'race_id'=> $this->race_id,
            'charclass_id'=> $this->charclass_id,
            'name' => $this->name,
            'atributes'=> $this->atributes,
            'spell_list'=> $this->spell_list,
            'equipment_list'=> $this->spell_list,
            'description' => $this->description,
            'created_at' => $this->created_at->format('d/m/Y'),
            'updated_at' => $this->updated_at->format('d/m/Y'),
        ];
    }
}
