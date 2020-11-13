<?php

use App\Http\Controllers\api\AccountController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\CommentController;
use App\Http\Controllers\api\PostController;
use App\Http\Controllers\api\PostVoteController;
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


Route::namespace('api')->group(
    function (){
        Route::get('post', [PostController::class, 'index']);
        Route::post('post', [PostController::class, 'store']);
        Route::get('post/{id}', [PostController::class, 'show']);
        Route::put('post', [PostController::class, 'update']);
        Route::get('postVote/{id}', [PostVoteController::class, 'show']);
        Route::put('postVote/{id}', [PostVoteController::class, 'update']);
        Route::get('comment', [CommentController::class, 'index']);
        Route::post('comment', [CommentController::class, 'create']);
        Route::group([
            'middleware' => 'api',
            'prefix' => 'auth'
        ], function ($router){
            Route::post('login', [AuthController::class,'login']);
            Route::post('logout', [AuthController::class,'logout']);
            Route::post('refresh', [AuthController::class,'refresh']);
            Route::post('me', [AuthController::class, 'me']);

        });
    }
);
