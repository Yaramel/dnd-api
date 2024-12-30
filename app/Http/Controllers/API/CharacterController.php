<?php

namespace App\Http\Controllers;

use App\Http\Resources\CharacterResource;
use App\Models\Character;
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
        $input = $request->all();

        $validator = Validator::make($input, [
            'camp_id' => 'required|exists:campaigns,id',
            'player_id' => 'required|exists:players,id',
            'race_id' => 'required|exists:races,id',
            'charclass_id' => 'required|exists:charclasses,id',
            'atributes' => 'required|array',
            'spell_list' => 'array',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Convert arrays to JSON
        $input['atributes'] = json_encode($input['atributes']);
        $input['spell_list'] = json_encode($input['spell_list']);

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
