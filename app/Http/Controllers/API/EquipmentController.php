<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\EquipmentResource;
use App\Models\Equipment;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

class EquipmentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Equipment::query();

        // Apply filter by type if provided
        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        // Get the filtered results
        $equipments = $query->get();
        $count = $equipments->count();

        return $this->sendResponse(EquipmentResource::collection($equipments), 'Equipments retrieved successfully.', $count);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $equipment = Equipment::find($id);
        if (!$equipment) {
            return response()->json(['message' => 'Equipment not found'], 404);
        }

        return response()->json(['data' => $equipment], 200);
    }
}
