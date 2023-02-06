<?php

use App\Http\Controllers\projectController;
use App\Http\Middleware\checkLoginMiddleware;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware(checkLoginMiddleware::class)->group(function () {
    // Route::get("employee",[projectController::class,'getEmployeeAPI'])->name("api.employee");
    // Route::get("project",function (){
    //    return response()->json(['project' => Project::all(),'status'=>200]);
    // })->name("api.project");
// });
