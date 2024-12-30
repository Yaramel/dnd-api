<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\RaceResource;
use App\Models\Race;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

class RaceController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $races = Race::get();
        $count = $races->count();
        return $this->sendResponse(RaceResource::collection($races), 'Races retrieved successfully.', $count);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $race = Race::find($id);
        if (!$race) {
            return response()->json(['message' => 'Race not found'], 404);
        }

        return response()->json(['data' => $race], 200);
    }
}
