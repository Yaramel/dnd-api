<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CampaignController;
use App\Http\Controllers\API\CharacterController;
use App\Http\Controllers\API\CharClassController;
use App\Http\Controllers\API\EquipmentController;
use App\Http\Controllers\API\RaceController;
use App\Http\Controllers\API\SpellController;
use App\Http\Controllers\API\CastController;
use App\Http\Controllers\API\InventoryController;
use App\Http\Middleware\RoleMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Endpoints to register, login and logout
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// Endpoints to create campaigns and homebrews
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':master'])->group(function () {
    Route::post('/campaigns/store', [CampaignController::class, 'store']);
    Route::put('/campaigns/update/{id}', [CampaignController::class, 'update']);
    Route::delete('/campaigns/delete/{id}', [CampaignController::class, 'destroy']);


    Route::post('/spells/store', [SpellController::class, 'store']);
    Route::put('/spells/update/{id}', [SpellController::class, 'update']);
    Route::delete('/spells/delete/{id}', [SpellController::class, 'destroy']);

    Route::post('/casts/store', [CastController::class, 'store']);
    Route::put('/casts/update/{id}', [CastController::class, 'update']);
    Route::delete('/casts/delete/{id}', [CastController::class, 'destroy']);
});

// Endpoints to create characters
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':player'])->group(function () {
    Route::post('/characters/store', [CharacterController::class, 'store']);
    Route::put('/characters/update/{id}', [CharacterController::class, 'update']);
    Route::delete('/characters/delete/{id}', [CharacterController::class, 'destroy']);

    Route::post('/inventorys/store', [InventoryController::class, 'store']);
    Route::put('/inventorys/update/{id}', [InventoryController::class, 'update']);
    Route::delete('/inventorys/delete/{id}', [InventoryController::class, 'destroy']);
});


Route::get('/campaigns', [CampaignController::class, 'index']);
Route::get('/campaigns/{id}', [CampaignController::class, 'show']);


Route::get('/characters', [CharacterController::class, 'index']);
Route::get('/characters/{id}', [CharacterController::class, 'show']);


Route::get('/spells', [SpellController::class, 'index']);
Route::get('/spells/{id}', [SpellController::class, 'show']);


Route::get('/casts', [CastController::class, 'index']);
Route::get('/casts/{id}', [CastController::class, 'show']);


Route::get('/inventorys', [InventoryController::class, 'index']);
Route::get('/inventorys/{id}', [InventoryController::class, 'show']);


Route::resource('races', RaceController::class);


Route::resource('classes', CharClassController::class);


Route::resource('equipments', EquipmentController::class);






