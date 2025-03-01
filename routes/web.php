<?php

use Illuminate\Support\Facades\Route;
use App\Models\Equipment;

Route::get('admin/equipment/{id}', function () {
    return view('equipment.show');
})->name('equipment.show');
// web.php (ルーティング設定)
Route::get('/qr-scanner', function () {
    return view('qrcode-scan-modal');  // Bladeビューを返す
})->name('qr-scanner');  // ルートに名前を付ける


Route::get('/', function () {
    return view('welcome');
});