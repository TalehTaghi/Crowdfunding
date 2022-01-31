<?php

use App\Http\Controllers\AuthController\LoginController;
use App\Http\Controllers\DonationController\DonationController;
use App\Http\Controllers\HomeController\GeneralController;
use App\Http\Controllers\ProjectsController\AnyProjectController;
use App\Http\Controllers\ProjectsController\OwnProjectController;
use App\Http\Middleware\isLogin;
use App\Http\Middleware\isLogout;
use Illuminate\Support\Facades\Route;

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

Route::middleware([isLogin::class])->group(function () {
    Route::get('/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login', [LoginController::class, 'loginAttempt'])->name('loginAttempt');
});

Route::middleware([isLogout::class])->group(function () {
    Route::get('/', [GeneralController::class, "index"])->name('index');
    Route::get('/logout', [LoginController::class, "logout"])->name('logout');

    Route::post("/investment/openModal", [DonationController::class, "getModalInfo"]);
    Route::post("/investment/donation", [DonationController::class, "donate"])->name('donation');

    Route::get("/project/view/{projectId}", [AnyProjectController::class, "projectView"])->name('project');
    Route::get("/myProjects/view", [OwnProjectController::class, "ownProjectsView"])->name('ownProjects');
});
