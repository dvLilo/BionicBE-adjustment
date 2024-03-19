<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\ActivityController;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FarmController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\LeadmanController;

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

  Route::resource("categories", CategoryController::class);
  Route::resource("farms", FarmController::class);
  Route::resource("buildings", BuildingController::class);
  Route::resource("buyers", BuyerController::class);
  Route::resource("leadmen", LeadmanController::class);
});

Route::get("dropdown", [TransactionController::class, "dropdown"]);
