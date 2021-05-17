<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskTimeController;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::match(['get', 'post'], '/', [TaskTimeController::class,'index']);

Route::get('/install', function () {
        return view('install');
});
Route::get('task/addPlan',[TaskTimeController::class,'addPlan']);
Route::post('task/addPlan',[TaskTimeController::class,'addPlanSave']);
Route::get('task/getPlan/', [TaskTimeController::class,'getPlan']);
Route::post('task/getPlan/', [TaskTimeController::class,'getPlan']);
Route::get('task/reports/', [TaskTimeController::class,'reports']);
Route::post('task/reports/', [TaskTimeController::class,'reportsSave']);

Route::get('task/reportsDays', [TaskTimeController::class,'reportsDays']);
Route::post('task/reportsDays', [TaskTimeController::class,'reportsDaysSave']);
Route::post('task/{id}/deleteTask', [TaskTimeController::class,'deleteTask']);

