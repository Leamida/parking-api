<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('parking-blocks', 'App\Http\Controllers\ParkingController@getAvailableBlocks');
Route::post('parking', 'App\Http\Controllers\ParkingController@parkVehicle');
Route::delete('parking/{blockId}/{slotId}', 'App\Http\Controllers\ParkingController@exitParking');
