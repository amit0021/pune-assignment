<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\PostController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware('auth:sanctum')->group(function(){
    Route::post('create-post',[PostController::class, 'create']);
    Route::post('edit-post',[PostController::class, 'edit']);
    Route::post('delete-post',[PostController::class, 'delete']);
});

Route::get('view-all-posts',[PostController::class, 'viewAllPosts']);


Route::post('/register', [HomeController::class,'register']);
Route::post('/login', [HomeController::class,'login']);
