<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CharacterResource;
use App\Models\Character;
use App\Models\CharClass;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;

class CharacterController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chars = Character::get();
        $count = $chars->count();
        return $this->sendResponse(CharacterResource::collection($chars), 'Characters retrieved successfully.', $count);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if the authenticated user is a player
        if (!auth()->user()->isPlayer()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $input = $request->all();

        // Ensure camp_id is empty on creation
        $input['camp_id'] = null;
        // Automatically assign the authenticated user's ID to the user_id
        $input['user_id'] = auth()->id();

        // Validate input
        $validator = Validator::make($input, [
            'race_id' => 'required|exists:races,id',
            'charclass_id' => 'required|exists:charclasses,id',
            'name' => 'required|string',
            'level' => 'required|integer|min:1|max:20',
            'atributes' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    $requiredKeys = ['str', 'con', 'dex', 'int', 'wis', 'cha'];
                    foreach ($requiredKeys as $key) {
                        if (!array_key_exists($key, $value)) {
                            $fail("The $attribute must contain the $key key.");
                        } elseif (!is_int($value[$key])) {
                            $fail("The $key value in $attribute must be an integer.");
                        }
                    }
                }
            ],
            'spell_list' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) use ($input) {
                    if (!empty($value)) {
                        $charclass = CharClass::find($input['charclass_id']);
                        if ($charclass->spellcast_atr === 'N/A') {
                            $fail("The selected charclass does not have spellcasting abilities, so $attribute cannot be filled.");
                        }
                    }
                }
            ],
            'equipment_list' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $requiredKeys = ['weapons', 'armors', 'itens'];
                    foreach ($requiredKeys as $key) {
                        if (!array_key_exists($key, $value)) {
                            $fail("The $attribute must contain the $key key.");
                        } elseif (!is_array($value[$key])) {
                            $fail("The $key in $attribute must be an array.");
                        }
                    }

                    // Validar itens dentro de cada array específico
                    foreach (['weapons', 'armors'] as $key) {
                        foreach ($value[$key] as $item) {
                            if (!isset($item['id']) || !is_int($item['id']) || !isset($item['equiped']) || !is_bool($item['equiped'])) {
                                $fail("Each item in $key must have 'id' (integer) and 'equiped' (boolean) keys.");
                            }
                        }
                    }
                }
            ],
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Convert arrays to JSON
        $input['atributes'] = json_encode($input['atributes']);
        if (!empty($input['spell_list'])) {
            $input['spell_list'] = json_encode($input['spell_list']);
        }else {
            $input['spell_list'] = null;
        }
        if (!empty($input['equipment_list'])) {
            $input['equipment_list'] = json_encode($input['equipment_list']);
        }

        $char = Character::create($input);

        return $this->sendResponse(new CharacterResource($char), 'Character created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $char = Character::find($id);
        if (!$char) {
            return response()->json(['message' => 'Character not found'], 404);
        }

        return response()->json(['data' => $char], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Character $char)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'camp_id' => 'exists:campaigns,id',
            'player_id' => 'exists:players,id',
            'race_id' => 'exists:races,id',
            'charclass_id' => 'exists:charclasses,id',
            'atributes' => 'array',
            'spell_list' => 'array',
            'description' => 'string',
            'equipment_list' => [
                'nullable',
                'array',
                function ($attribute, $value, $fail) {
                    $requiredKeys = ['weapons', 'armors', 'itens'];
                    foreach ($requiredKeys as $key) {
                        if (!array_key_exists($key, $value)) {
                            $fail("The $attribute must contain the $key key.");
                        } elseif (!is_array($value[$key])) {
                            $fail("The $key in $attribute must be an array.");
                        }
                    }

                    // Validar itens dentro de cada array específico
                    foreach (['weapons', 'armors'] as $key) {
                        foreach ($value[$key] as $item) {
                            if (!isset($item['id']) || !is_int($item['id']) || !isset($item['equiped']) || !is_bool($item['equiped'])) {
                                $fail("Each item in $key must have 'id' (integer) and 'equiped' (boolean) keys.");
                            }
                        }
                    }
                }
            ],
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Only update fields that are present in the request
        if (array_key_exists('atributes', $input)) {
            $atributes = $input['atributes'];
            if (!is_null($char->atributes)) {
                // Decode existing atributes
                $existingAtributes = json_decode($char->atributes, true);

                // Merge new atributes with existing ones
                $mergedAtributes = array_merge($existingAtributes, $atributes);

                // Encode back to JSON
                $char->atributes = json_encode($mergedAtributes);
            } else {
                // No existing attributes, just set the new ones
                $char->atributes = json_encode($atributes);
            }
        }

        if (array_key_exists('spell_list', $input)) {
            $char->spell_list = json_encode($input['spell_list']);
        }

        // Update other fields if present
        $char->fill($input);
        $char->save();

        return $this->sendResponse(new CharacterResource($char), 'Character updated successfully.');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Character $char)
    {
        $char->delete();
        return $this->sendResponse([], 'Character deleted successfully.');
    }

}
