<?php

use App\Http\Controllers\AnimeController;
use Illuminate\Support\Facades\Route;


Route::get( '/anime-data',[AnimeController::class,'fetchAndStoreAnime'] );
Route::get('/anime/{slug}',[AnimeController::class,'getAnime'] );
