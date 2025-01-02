<?php

namespace App\Http\Controllers\APi;

use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Validator;

class InventoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventorys = Inventory::get();
        $count = $inventorys->count();
        return $this->sendResponse(InventoryResource::collection($inventorys), 'Inventory retrieved successfully.', $count);
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

        $validator = Validator::make($input, [
            'char_id'=> 'required|exists:characters,id',
            'equipment_id'=> 'required|exists:equipments,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $inventory = Inventory::create($input);

        return $this->sendResponse(new InventoryResource($inventory), 'Inventory created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $inventory = Inventory::find($id);
        if (!$inventory) {
            return response()->json(['message' => 'Inventory not found'], 404);
        }

        return response()->json(['data' => $inventory], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
