<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\SpellResource;
use App\Models\Spell;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;

class SpellController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $spells = Spell::get();
        $count = $spells->count();
        return $this->sendResponse(SpellResource::collection($spells), 'Spells retrieved successfully.', $count);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // Check if the authenticated user is a master
        if (!auth()->user()->isMaster()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $input = $request->all();

        // Check if the authenticated user has the 'master' role
        // $user = $request->user();
        // if ($user->role !== 'master') {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Only masters can create spells.',
        //     ], 403);
        // }

        // Automatically assign the authenticated user's ID to the user_id
        $input['user_id'] = auth()->id();

        // Prevent creation if user_id = 1 (core spell restriction)
        if ($input['user_id'] == 1) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot create a core spell.',
            ], 403);
        }

        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'level' => 'required|integer|min:0|max:9',
            'casting_time' => 'required|string|max:255',
            'range' => 'required|string|max:255',
            'area' => 'required|json',
            'components' => 'required|json',
            'duration' => 'required|string|max:255',
            'school' => 'required|string|max:255',
            'attack_save' => 'required|json',
            'description' => 'required|string',
            'damage_effect' => 'required|string|max:255',
            'damage' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $spell = Spell::create($input);

        return $this->sendResponse(new SpellResource($spell), 'Spell created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id)
    {
        $spell = Spell::find($id);
        if (!$spell) {
            return response()->json(['message' => 'Spell not found'], 404);
        }

        return response()->json(['data' => $spell], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Spell $spell
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Spell $spell)
    {
        $input = $request->all();

        // Prevent updates if master_id is 1
        if ($spell->master_id == 1) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot update a core spell.',
            ], 403);
        }

        $validator = Validator::make($input, [
            'name' => 'string|max:255',
            'level' => 'integer|min:0|max:9',
            'description' => 'string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($request->has('name')) {
            $spell->name = $request->input('name');
        }
        if ($request->has('level')) {
            $spell->level = $request->input('level');
        }
        if ($request->has('description')) {
            $spell->description = $request->input('description');
        }
        $spell->save();

        return $this->sendResponse(new SpellResource($spell), 'Spell updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Spell $spell
     * @return \Illuminate\Http\Response
     */
    public function destroy(Spell $spell)
    {
        // Prevent deletion if master_id is 1
        if ($spell->master_id == 1) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete a core spell.',
            ], 403);
        }

        $spell->delete();
        return $this->sendResponse([], 'Spell deleted successfully.');
    }
}
