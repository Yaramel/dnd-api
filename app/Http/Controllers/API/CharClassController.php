<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CharClassResource;
use App\Models\CharClass;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

class CharClassController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $classes = CharClass::get();
        $count = $classes->count();
        return $this->sendResponse(CharClassResource::collection($classes), 'Classes retrieved successfully.', $count);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $class = CharClass::find($id);
        if (!$class) {
            return response()->json(['message' => 'Class not found'], 404);
        }

        return response()->json(['data' => $class], 200);
    }
}
