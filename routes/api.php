<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\ContactController;
use App\Http\Helpers;

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

Route::get('/pages/all', [PageController::class, 'listPages']);
Route::get('/getPages', [PageController::class, 'readPages']);
Route::get('/getContent/{page}', [PageController::class, 'readPage']);
Route::get('carousel/get', [CarouselController::class, 'getCarousel']);
Route::get('news/get/', [NewsController::class, 'read']);
Route::get('news/get/limit/{limit}', [NewsController::class, 'readLimit']);
Route::get('news/get/{slug}', [NewsController::class, 'readItem']);
Route::get('videos/get', [NewsController::class, 'getVideos']);
Route::get('videos/get/limit/{limit}', [NewsController::class, 'getVideosLimit']);
Route::get('videos/get/{slug}', [NewsController::class, 'getVideo']);
Route::get('podcasts/get', [NewsController::class, 'getPodcasts']);
Route::get('podcasts/get/limit/{limit}', [NewsController::class, 'getPodcastsLimit']);
Route::get('podcasts/get/{slug}', [NewsController::class, 'getPodcast']);

Route::get('/getLandingPage', [NewsController::class, 'getLandingPage']);

Route::group(['middleware' => ['token.valid']], function() {
    Route::post('/newPage', [PageController::class, 'createPage']);
    Route::put('/updatePage', [PageController::class, 'updatePage']);
    Route::put('/contato/update', [ContactController::class, 'update']);
    Route::put('/setOrder', [PageController::class, 'setOrder']);
    Route::delete('/deletePage/{page}', [PageController::class, 'deletePage']);
    
    Route::post('carousel/new', [CarouselController::class, 'createItem']);
    Route::delete('carousel/delete/{id}', [CarouselController::class, 'delete']);
    
    Route::post('news/new', [NewsController::class, 'create']);
    Route::delete('news/delete/{id}', [NewsController::class, 'delete']);
    
    Route::post('/saveImage', function(Request $req) {
        $responseJson = $req->hasFile('image') ? ["url" => (new Helpers)->createImageLink($req->file('image'))] : ['error' => 'Erro ao salvar imagem'];
        return response()->json($responseJson);
    });
});