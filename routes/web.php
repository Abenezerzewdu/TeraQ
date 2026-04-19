<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueueController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('home');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/businesses', [BusinessController::class, 'store']);
Route::get('/businesses/create', [BusinessController::class, 'create'])->name('businesses.create');
});
Route::post('/queues/{queue}/join',[QueueController::class,'join']);

Route::get('/queues/{queue}',[QueueController::class,'show']);


Route::get('/join',function(){
return Inertia::render('QueuePage');
});


Route::get('/businesses/{business}', [BusinessController::class, 'show'])->name('business.show');
Route::get('/businesses',[BusinessController::class,'index'])->name('businesses');
Route::get('/support',function(){
   return Inertia::render('Support');
})->name('support');

Route::post('/businesses/{business}/queues', [QueueController::class, 'store']);
require __DIR__.'/auth.php';
