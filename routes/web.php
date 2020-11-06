<?php

use App\Http\Controllers\ClubController;
use App\Http\Controllers\ClubMemberController;
use App\Http\Controllers\ClubRoleController;
use App\Http\Controllers\CurrentClubController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Mockery\Matcher\Not;

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

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('/clubs', function () {
        return Inertia::render('Clubs');
    })->name('clubs');

    Route::put('/current-club', [UserController::class, 'update_current_club'])->name('current-club.update');
    Route::put('/club/information', [CurrentClubController::class, 'update_informations'])->name('club-information.update');

    Route::post('/club-roles', [ClubRoleController::class, 'store'])->name('club-roles.store');
    Route::put('/club-roles/{role}', [ClubRoleController::class, 'update'])->name('club-roles.update');
    Route::delete('/club-roles/{role}', [ClubRoleController::class, 'delete'])->name('club-roles.delete');

    Route::post('/club/{club}/members', [ClubMemberController::class, 'store'])->name('club-members.store');
    Route::patch('/club/{club}/user/{user}/join', [ClubMemberController::class, 'join'])->name('club-members.join');
    Route::delete('/club/{club}/user/{user}', [ClubMemberController::class, 'delete'])->name('club-members.delete');

    Route::patch('/notification/{notification}/mark-as-read', [NotificationController::class, 'mark_as_read'])->name('notifications.read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'delete'])->name('notification.delete');

    Route::resource('clubs', ClubController::class)->except([
        'index',
    ]);
});
