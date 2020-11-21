<?php

use App\Http\Controllers\Api\ProductsController;
use App\Http\Controllers\Api\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('v1/products/{product}', [ProductsController::class, 'show']);
Route::patch('v1/products/{product}', [ProductsController::class, 'update']);
Route::delete('v1/products/{product}', [ProductsController::class, 'destroy']);
Route::get('v1/stats', [ProductsController::class, 'stats']);
Route::resource('v1/products/', ProductsController::class);

Route::get('v1/warehouse/', WarehouseController::class);
Route::get('v1/warehouse/all', [WarehouseController::class, 'index']);
