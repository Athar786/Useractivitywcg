<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth','employee-access:admin'])->group(function() {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/emp', [EmployeeController::class, 'index']);
    Route::post('/store', [EmployeeController::class, 'store'])->name('store');
    Route::get('/fetchall', [EmployeeController::class, 'fetchAll'])->name('fetchAll');
    Route::delete('/delete', [EmployeeController::class, 'delete'])->name('delete');
    Route::get('/edit', [EmployeeController::class, 'edit'])->name('edit');
    Route::post('/update', [EmployeeController::class, 'update'])->name('update');

    Route::get('add-to-log',[HomeController::class,'myTestAddToLog']);
    Route::get('logActivity',[HomeController::class,'logActivity'])->name('logActivity');
});
Auth::routes();


