<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueueController;
use App\Services\NotificationService;
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
    return Inertia::render('Dashboard', [
        'active_entries' => auth()->user()->queueEntries()
            ->with('queue.business')
            ->whereIn('status', ['waiting', 'serving'])
            ->get(),
        'owned_businesses' => auth()->user()->businesses()
            ->withCount(['queues'])
            ->get(),
    ]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/businesses', [BusinessController::class, 'store'])->name('businesses.store');
    Route::get('/businesses/create', [BusinessController::class, 'create'])->name('businesses.create');
    Route::get('/businesses/{business}/edit', [BusinessController::class, 'edit'])->name('businesses.edit');
    Route::patch('/businesses/{business}', [BusinessController::class, 'update'])->name('businesses.update');
    Route::delete('/businesses/{business}', [BusinessController::class, 'destroy'])->name('businesses.destroy');
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

Route::post('/queues/{queue}/leave', [QueueController::class, 'leave'])->name('queues.leave');
Route::post('/queues/{queue}/update-phone', [QueueController::class, 'updatePhone'])->name('queues.update-phone');
Route::post('/queues/{queue}/start-next', [QueueController::class, 'startNext'])->name('queues.start-next');
Route::post('/queues/{queue}/entries/{entry}/remove', [QueueController::class, 'removeEntry'])->name('queues.remove-entry');

Route::post('/businesses/{business}/queues', [QueueController::class, 'store']);

Route::get('/test-sms', function () {
    app(NotificationService::class)->sendSMS(
        '+251941031450', // your phone
        'TeraQ test message'
    );

    return 'SMS sent (check your phone)';
});
// TEMPORARY: diagnostic route to check web server PHP config
Route::get('/debug-upload', function () {
    return response()->json([
        'upload_tmp_dir' => ini_get('upload_tmp_dir'),
        'sys_temp_dir' => sys_get_temp_dir(),
        'upload_max_filesize' => ini_get('upload_max_filesize'),
        'post_max_size' => ini_get('post_max_size'),
        'file_uploads' => ini_get('file_uploads'),
        'tmp_writable' => is_writable(sys_get_temp_dir()),
        'loaded_ini' => php_ini_loaded_file(),
        'sapi' => php_sapi_name(),
    ]);
});

require __DIR__.'/auth.php';
