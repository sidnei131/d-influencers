<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\InfluencerController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\GuestMiddleware;

Route::middleware(GuestMiddleware::class)->group(function() {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::apiResource('users', UserController::class);

Route::middleware(AuthMiddleware::class)->group(function() {
    Route::get('me', [AuthController::class, 'me'])->name('me');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');

    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/{id}', [CampaignController::class, 'show'])->name('campaigns.show');
    Route::post('/campaigns', [CampaignController::class, 'store'])->name('campaigns.store');
    Route::put('/campaigns/{id}', [CampaignController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{id}', [CampaignController::class, 'destroy'])->name('campaigns.destroy');

    Route::get('/influencers', [InfluencerController::class, 'index'])->name('influencers.index');
    Route::get('/influencers/{id}', [InfluencerController::class, 'show'])->name('influencers.show');
    Route::post('/influencers', [InfluencerController::class, 'store'])->name('influencers.store');
    Route::put('/influencers/{id}', [InfluencerController::class, 'update'])->name('influencers.update');
    Route::delete('/influencers/{id}', [InfluencerController::class, 'destroy'])->name('influencers.destroy');
});
