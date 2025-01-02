<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CampaignResource;
use App\Models\Campaign;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;

class CampaignController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $camps = Campaign::get();
        $count = $camps->count();
        return $this->sendResponse(CampaignResource::collection($camps), 'Campaigns retrieved successfully.', $count);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if the authenticated user is a master
        if (!auth()->user()->isMaster()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $input = $request->all();

        // Prevent creation if master_id is 1
        if (isset($input['master_id']) && $input['master_id'] == 1) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot create a core campaign.',
            ], 403);
        }

        // Automatically assign the authenticated user's ID to the user_id
        $input['user_id'] = auth()->id();

        $validator = Validator::make($input, [
            'name' => 'required|string|max:255',
            'num_chars' => 'required|integer|min:1',
            'setting' => 'required|string|max:255',
            'homebrew_list' => 'nullable|array',
            'ban_list' => 'nullable|array',
            'ban_list.classes' => 'array|nullable',
            'ban_list.races' => 'array|nullable',
            'ban_list.spells' => 'array|nullable',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Convert ban_list array to JSON
        if (!empty($input['ban_list'])) {
            $input['ban_list'] = json_encode($input['ban_list']);
        } else {
            $input['ban_list'] = null;
        }

        // Apenas codificar para JSON se não for vazio
        if (!empty($input['homebrew_list'])) {
            $input['homebrew_list'] = json_encode($input['homebrew_list']);
        }

        if (!empty($input['homebre_list'])) {
            $input['homebrew_list'] = json_encode($input['homebrew_list']);
        } else {
            $input['homebrew_list'] = null;
        }

        $camp = Campaign::create($input);

        return $this->sendResponse(new CampaignResource($camp), 'Campaign created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $camp = Campaign::find($id);
        if (!$camp) {
            return response()->json(['message' => 'Campaign not found'], 404);
        }

        return response()->json(['data' => $camp], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Campaign $camp)
    {
        $input = $request->all();

        // Prevent updates if master_id is 1
        if ($camp->master_id == 1) {
            return response()->json([
                'success' => false,
                'message' => 'Campaign not found.',
            ], 403);
        }

        $validator = Validator::make($input, [
            'name' => 'string|max:255',
            'num_players' => 'integer|min:1',
            'description' => 'string',
            'char_list' => 'array',
            'ban_list' => 'nullable|array',
            'ban_list.classes' => 'array|nullable',
            'ban_list.races' => 'array|nullable',
            'ban_list.spells' => 'array|nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($request->has('name')) {
            $camp->name = $request->input('name');
        }
        if ($request->has('num_players')) {
            $camp->num_players = $request->input('num_players');
        }
        if ($request->has('description')) {
            $camp->description = $request->input('description');
        }
        if ($request->has('char_list')) {
            $camp->char_list = json_encode($input['char_list']);
        }
        if ($request->has('ban_list')) {
            $camp->ban_list = json_encode($input['ban_list']);
        }
        $camp->save();

        return $this->sendResponse(new CampaignResource($camp), 'Campaign updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Campaign $camp)
    {
        $camp->delete();
        return $this->sendResponse([], 'Campaign deleted successfully.');
    }
}
