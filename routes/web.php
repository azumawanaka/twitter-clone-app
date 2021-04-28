<?php

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

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::post('/home/store', [App\Http\Controllers\HomeController::class, 'store'])->name('tweet.store');
    Route::post('/home/{tweetId}/update', [App\Http\Controllers\HomeController::class, 'updateTweet'])->name('tweet.update');
    Route::get('/home/{tweetId}/remove', [App\Http\Controllers\HomeController::class, 'removeTweet'])->name('tweet.remove');
    Route::post('/home/{tweetId}/comment', [App\Http\Controllers\HomeController::class, 'comment'])->name('tweet.comment');
    Route::post('/home/{commentId}/update', [App\Http\Controllers\HomeController::class, 'updateComment'])->name('tweet.comment.update');
    Route::get('/home/{commentId}/remove', [App\Http\Controllers\HomeController::class, 'removeComment'])->name('tweet.comment.remove');
    Route::post('/home/comment/{commentId}', [App\Http\Controllers\HomeController::class, 'reply'])->name('tweet.reply');
    Route::get('/home/reply/{replyId}/remove', [App\Http\Controllers\HomeController::class, 'removeReply'])->name('comment.reply.remove');
    Route::post('/home/reply/{replyId}/update', [App\Http\Controllers\HomeController::class, 'updateReply'])->name('tweet.reply.update');

    Route::get('/home/follow/{userId}', [App\Http\Controllers\HomeController::class, 'follow'])->name('tweet.user.follow');
    Route::get('/home/{userId}/unFollow/{followerId}', [App\Http\Controllers\HomeController::class, 'unFollow'])->name('tweet.user.unFollow');

    Route::get('/chats', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');
    Route::post('/chats/msg', [App\Http\Controllers\ChatController::class, 'message'])->name('chat.message');
});
