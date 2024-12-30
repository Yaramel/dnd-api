<?php

namespace App\Http\Controllers;

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
        $input = $request->all();

        // Prevent creation if master_id is 1
        if (isset($input['master_id']) && $input['master_id'] == 1) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot create a core campaign.',
            ], 403);
        }

        $validator = Validator::make($input, [
            'master_id' => 'required|exists:masters,id',
            'name' => 'required|string|max:255',
            'num_players' => 'required|integer|min:1',
            'description' => 'required|string',
            'char_list' => 'array',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Convert array to JSON
        $input['char_list'] = json_encode($input['char_list']);

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
