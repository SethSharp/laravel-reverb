<?php

use App\Events\HelloWorld;
use App\Events\MessageReceived;
use App\Events\PersonalMessageReceived;
use App\Events\PrivateMessageReceived;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    HelloWorld::dispatch();
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/visit-count', function () {
    return Inertia::render('VisitCount');
});

Route::get('/messages', function () {
    return Inertia::render('Messages');
});

Route::post('/send-message', function () {
    $data = request()->validate([
        'message' => 'required|string',
        'id' => 'required'
    ]);

    MessageReceived::dispatch($data['message'], $data['id']);

    return response()
        ->json([
            'message' => 'Message successfully sent!'
        ]);
});

Route::post('/send-private-message', function () {
    $data = request()->validate([
        'message' => 'required|string',
        'id' => 'required'
    ]);

    PrivateMessageReceived::dispatch($data['message'], $data['id']);

    return response()
        ->json([
            'message' => 'Message successfully sent!'
        ]);
});

Route::post('/send-private-personal-message', function () {
    $data = request()->validate([
        'message' => 'required|string',
        'id' => 'required'
    ]);

    PersonalMessageReceived::dispatch($data['message'], $data['id']);

    return response()
        ->json([
            'message' => 'Message successfully sent!'
        ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/my-messages', function () {
    return Inertia::render('PrivateMessages');
})->middleware(['auth', 'verified'])->name('my-messages');

Route::get('/personal-messages', function () {
    return Inertia::render('PersonalMessages', [
        'user' => auth()->user()
    ]);
})->middleware(['auth', 'verified'])->name('personal-messages');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
