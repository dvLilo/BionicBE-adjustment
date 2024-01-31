<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\ActivityController;

use Spatie\Activitylog\Models\Activity;

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

Route::post("login", [UserController::class, "login"]);

Route::group(["middleware" => "auth:sanctum"], function () {
  Route::post("logout", [UserController::class, "logout"]);

  Route::resource("transactions", TransactionController::class);
  Route::resource("informations", InformationController::class);

  Route::resource("activities", ActivityController::class);
});

Route::get("dropdown", [TransactionController::class, "dropdown"]);
