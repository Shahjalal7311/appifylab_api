<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('welcome');});
Route::prefix('web/v1')->group(function () {
  
});