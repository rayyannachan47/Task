<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [AdminController::class, 'login']);
Route::post('/checklogin', [AdminController::class, 'CheckLogin'])->name('checklogin');
Route::get('/logout', [AdminController::class, 'Logout'])->name('logout');
Route::get('/changePassword/{id}', [AdminController::class, 'changePassword'])->name('/changePassword');
Route::post('/checkpassword', [AdminController::class, 'checkpassword'])->name('checkpassword');
Route::post('/checksession', [AdminController::class, 'checkSession'])->name('checksession');

// Admin
Route::get('/Admin/Dashboard', [AdminController::class, 'dashboard'])->name('/Admin/Dashboard')->middleware('role:1');
Route::post('/Admin/show_user_data', [AdminController::class, 'userData'])->name('/Admin/show_user_data')->middleware('role:1');
Route::get('/Admin/add_user', [AdminController::class, 'addUser'])->name('/Admin/add_user')->middleware('role:1');
Route::post('/Admin/add_user_code', [AdminController::class, 'add_user_code'])->name('/Admin/add_user_code')->middleware('role:1');
Route::get('/Admin/delete_user/{id}', [AdminController::class, 'delete_user'])->name('Admin/delete_user')->middleware('role:1');
Route::get('/Admin/edit_user/{userId}', [AdminController::class, 'edit_user'])->name('Admin/edit_user')->middleware('role:1');
Route::post('/Admin/edit_user_code', [AdminController::class, 'edit_user_code'])->name('/Admin/edit_user_code')->middleware('role:1');

// User

Route::get('/User/Dashboard/', [AdminController::class, 'userDashboard'])->name('/User/Dashboard')->middleware('role:2');
