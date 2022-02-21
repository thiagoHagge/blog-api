<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\NewsController;

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
Route::post('/auth', UserController::class);
Route::post('/check', [UserController::class, 'checkToken']);
Route::post('/newPage', [PageController::class, 'createPage']);
Route::get('/getPages', [PageController::class, 'readPages']);
Route::put('/updatePage', [PageController::class, 'updatePage']);
Route::get('/getContent/{page}', [PageController::class, 'readPage']);
Route::put('/setOrder', [PageController::class, 'setOrder']);
Route::delete('/deletePage/{page}', [PageController::class, 'deletePage']);

Route::post('carousel/new', [CarouselController::class, 'createItem']);
Route::get('carousel/get', [CarouselController::class, 'getItems']);
Route::delete('carousel/delete/{id}', [CarouselController::class, 'delete']);

Route::post('news/new', [NewsController::class, 'create']);
Route::get('news/get/', [NewsController::class, 'read']);
Route::get('news/get/{slug}', [NewsController::class, 'readItem']);
// Teste
Route::post('/saveImage', [CarouselController::class, 'saveImage']);
