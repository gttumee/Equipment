<?php

use Illuminate\Support\Facades\Route;
use App\Models\Equipment;

Route::get('/equipment/{id}', function () {
    return view('equipment.show');
})->name('equipment.show');

Route::get('/', function () {
    return view('welcome');
});