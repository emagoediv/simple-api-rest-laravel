<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/auth', [AuthController::class,"login"]);

Route::group(["middleware" => ["jwt.auth"]],function () {
    Route::get("/user",[UserController::class,"index"])->name("users.index");
    Route::get("/user/{id}",[UserController::class,"show"])->name("users.show");
    Route::post("/user",[UserController::class,"store"])->name("users.store");
    Route::patch("/user/{id}",[UserController::class,"update"])->name("users.update");
    Route::delete("/user/{id}",[UserController::class,"destroy"])->name("users.destroy");

    Route::post("/auth/logout",[AuthController::class,"logout"])->name("auth.logout");
    Route::post("/auth/refresh",[AuthController::class,"refresh"])->name("auth.refresh");
});


Route::get("/", function () {
    return response()->json([
        "message"=>"success"
    ]);
});