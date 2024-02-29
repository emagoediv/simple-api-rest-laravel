<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get("/user",[UserController::class,"index"])->name("users.index");
Route::get("/user/{id}",[UserController::class,"show"])->name("users.show");
Route::post("/user",[UserController::class,"store"])->name("users.store");
Route::patch("/user/{id}",[UserController::class,"update"])->name("users.update");
Route::delete("/user/{id}",[UserController::class,"destroy"])->name("users.destroy");

Route::get("/", function () {
    return response()->json([
        "message"=>"success"
    ]);
});