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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('/home/store', [App\Http\Controllers\HomeController::class, 'store'])->name('tweet.store');
Route::get('/home/{tweetId}/remove', [App\Http\Controllers\HomeController::class, 'removeTweet'])->name('tweet.remove');
Route::post('/home/{tweetId}/comment', [App\Http\Controllers\HomeController::class, 'comment'])->name('tweet.comment');
Route::get('/home/remove/{commentId}', [App\Http\Controllers\HomeController::class, 'removeComment'])->name('tweet.comment.remove');
Route::post('/home/comment/{commentId}', [App\Http\Controllers\HomeController::class, 'reply'])->name('tweet.reply');
Route::get('/home/reply/remove/{replyId}', [App\Http\Controllers\HomeController::class, 'removeReply'])->name('comment.reply.remove');
