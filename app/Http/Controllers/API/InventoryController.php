<?php

namespace App\Http\Controllers\APi;

use App\Http\Resources\InventoryResource;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;

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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
