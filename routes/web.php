<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\ThreadController as AdminThreadController;

Route::get('/', function ($guard = null) {
    if (Auth::guard($guard)->check()) {
        return redirect('/threads');
    }
    return redirect('/login');
            
});

Auth::routes(['verify' => true]);

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['throttle:6,1'])->withoutMiddleware('verified')->name('verification.send');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/dashboard');
})->middleware(['signed'])->withoutMiddleware('verified')->name('verification.verify');

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->withoutMiddleware('verified')->name('verification.notice');

Route::middleware(['auth','verified'])->group(function () { 
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    
    Route::get('/threads', [ThreadController::class, 'index'])->name('thread.index');
    Route::get('/threads/create', [ThreadController::class, 'create'])->name('thread.create');
    Route::get('threads/{category}/{thread}/edit', [ThreadController::class, 'edit']);
    Route::patch('threads/{category}/{thread}', [ThreadController::class, 'update'])->name('edit.update');
    Route::get('/threads/{category}', [ThreadController::class, 'index'])->name('thread.category');
    Route::post('/threads', [ThreadController::class, 'store'])->name('thread.store');
    Route::get('/threads/{category}/{thread}', [ThreadController::class, 'show'])->name('thread.show');
    Route::delete('threads/{category}/{thread}', [ThreadController::class, 'destroy'])->name('thread.destroy');

    Route::post('/threads/{category}/{thread}/replies', [ReplyController::class, 'store'])->name('reply.store');
    Route::patch('/threads/{reply}', [ReplyController::class, 'update'])->name('reply.update');
    Route::delete('/threads/{category}/{thread}/replies/{reply}', [ReplyController::class, 'destroy'])->name('reply.destroy');

    Route::post('/replies/{reply}/likes', [LikeController::class, 'storeReplyLikes'])->name('reply.store');
    Route::post('/threads/{thread}/likes', [LikeController::class, 'storeThreadLikes'])->name('thread.store');

    Route::get('/profiles/{user}', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/dashboard', [ProfileController::class, 'store'])->name('user.upload');
});

Route::middleware(['auth','hasRole'])->group(function () { 
    Route::get('/admin_dashboard', [App\Http\Controllers\HomeController::class, 'admin'])->name('admin.dashboard');
    Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::get('/admin/threads', [AdminThreadController::class, 'index'])->name('threads.index');
    Route::patch('/admin/locked-thread/{thread}', [AdminThreadController::class, 'lock'])->name('locked-thread.store');
    Route::patch('/admin/unlocked-thread/{thread}', [AdminThreadController::class, 'unlock'])->name('unlocked-thread.store');
    Route::patch('/admin/archive-thread/{thread}', [AdminThreadController::class, 'archive'])->name('archive-thread.store');
    Route::patch('/admin/unarchive-thread/{thread}', [AdminThreadController::class, 'unarchive'])->name('unarchive-thread.store');
    Route::patch('/admin/restore-thread/{thread}', [AdminThreadController::class, 'restore'])->name('restore-thread.store');
    Route::delete('/admin/delete-thread/{thread}', [AdminThreadController::class, 'delete'])->name('delete-thread.store');
    
});


Route::get('/admin_dashb', [App\Http\Controllers\HomeController::class, 'index'])->name('replies.index');
Route::get('/admin_dashbod', [App\Http\Controllers\HomeController::class, 'index'])->name('category.index');
