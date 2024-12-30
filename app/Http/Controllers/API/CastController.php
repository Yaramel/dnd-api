<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CastResource;
use App\Models\Cast;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;

class CastController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $casts = Cast::get();
        $count = $casts->count();
        return $this->sendResponse(CastResource::collection($casts), 'Casts retrieved successfully.', $count);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'spell_id' => 'required|exists:spells,id',
            'charclass_id' => 'required|exists:charclasses,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Fetch the charclass to check its spellcasting_atr
        // $charclass = CharClass::find($input['charclass_id']);
        // if ($charclass->spellcasting_atr === 'N/A') {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'The selected charclass does not have spellcasting abilities.',
        //     ], 403);
        // }

        $cast = Cast::create($input);

        return $this->sendResponse(new CastResource($cast), 'Cast created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cast = Cast::find($id);
        if (!$cast) {
            return response()->json(['message' => 'Cast not found'], 404);
        }

        return response()->json(['data' => $cast], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cast $cast)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'charclass_id' => 'required|exists:charclasses,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Fetch the charclass to check its spellcasting_atr
        // $charclass = CharClass::find($input['charclass_id']);
        // if ($charclass->spellcasting_atr === 'N/A') {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'The selected charclass does not have spellcasting abilities.',
        //     ], 403);
        // }

        // Check if the related spell's master_id is 1
        if ($cast->spell->master_id == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Updates are not allowed for core spells.',
            ], 403);
        }

        $cast->charclass_id = $request->input('charclass_id');
        $cast->save();

        return $this->sendResponse(new CastResource($cast), 'Cast updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cast $cast)
    {
        // Prevent deletion if master_id is 1
        if ($cast->spell->master_id == 1) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete a core spell.',
            ], 403);
        }

        $cast->delete();
        return $this->sendResponse([], 'Cast deleted successfully.');
    }
}
