<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EquipmentController;
use App\Http\Controllers\EstablishmentController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\RadioactiveSourceController;
use App\Http\Controllers\UsageAuthorizationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('establishments', EstablishmentController::class);
Route::resource('inspections', InspectionController::class);
Route::post('/inspections/{inspection}/approve', [InspectionController::class, 'approve'])->name('inspections.approve');
Route::resource('radioactive-sources', RadioactiveSourceController::class);
Route::resource('usage-authorizations', UsageAuthorizationController::class);
Route::resource('equipment', EquipmentController::class);
Route::resource('inspectors', InspectorController::class);
