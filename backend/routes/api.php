<?php

use App\Http\Controllers\api\AccountController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\CommentVoteController;
use App\Http\Controllers\api\LoginController;
use App\Http\Controllers\api\NotificationController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\PostVoteController;
use App\Http\Controllers\api\SubjectController;
use App\Http\Controllers\api\TagController;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Route::namespace('api')->group(function () {
//
//    Route::get('error', function (){
//        return response()->json(['error' => 'unauthenticated']);
//    })->name('error');
//    Route::get('post', [PostController::class, 'index'])->name('post');
//    Route::post('login', [LoginController::class, 'login'])->name('login');
//    Route::middleware('auth:api')->group(function (){
//        Route::get('comment', [CommentController::class, 'index']);
//        Route::post('logout', [LoginController::class, 'logout']);
//        Route::post('register', [LoginController::class, 'register']);
//        Route::post('me', [LoginController::class, 'me'])->name('me');
//        Route::get('postVote/{id}', [PostVoteController::class, 'show']);
//        Route::put('postVote/{id}', [PostVoteController::class, 'update']);
//        Route::post('comment', [CommentController::class, 'create']);
//        Route::post('post', [PostController::class, 'store']);
//        Route::get('post/{id}', [PostController::class, 'show']);
//        Route::put('post', [PostController::class, 'update']);
//        Route::get('account', [AccountController::class, 'getBase']);
//        Route::get('notification', [NotificationController::class, 'index']);
//    });
//});
Route::group([
    'middleware' => 'api',
], function () {
    //login - register
    Route::post('login', [AuthController::class, 'login']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('signup', [AuthController::class, 'signup']);
    //comment
    Route::get('comment', [CommentController::class, 'index']);
    Route::post('comment', [CommentController::class, 'store']);
    Route::delete('comment/{id}', [CommentController::class, 'destroy']);
    Route::put('comment/{id}', [CommentController::class, 'update']);
    // comment vote
    Route::get('commentVote/{id}', [CommentVoteController::class, 'show']);
    Route::delete('commentVote/{id}', [CommentVoteController::class, 'destroy']);
    Route::post('commentVote/{id}', [CommentVoteController::class, 'store']);
    // post
    Route::get('post', [PostController::class, 'index']);
    Route::post('post', [PostController::class, 'store']);
    Route::get('post/{id}', [PostController::class, 'show']);
    Route::put('post/{id}', [PostController::class, 'update']);
    Route::delete('post/{id}', [PostController::class, 'destroy']);
    //post vote
    Route::get('postVote/{id}', [PostVoteController::class, 'show']);
    Route::delete('postVote/{id}', [PostVoteController::class, 'destroy']);
    Route::post('postVote/{id}', [PostVoteController::class, 'store']);

    // account
    Route::get('account', [AccountController::class, 'getBase']);
    Route::put('account', [AccountController::class, 'update']);
    Route::get('notification', [NotificationController::class, 'index']);
    Route::get('subject', [SubjectController::class, 'index']);
    Route::get('account/summary', [AccountController::class, 'summary']);
    Route::get('tag', [TagController::class, 'index']);
    Route::get('account/post', [AccountController::class, 'postOfUser']);
    Route::get('account/activity', [AccountController::class, 'activity']);
    // like post/comment
    Route::get('like/post/{id}', [PostVoteController::class, 'isLike']);
    Route::get('like/comment/{id}', [CommentVoteController::class, 'isLike']);
});
