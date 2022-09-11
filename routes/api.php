<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/import-from-excel','ImportFromExcelController@importProjectsAndContracts')->name('import-from-excel');
//Route::get('/users', function () {
//    return \App\User::all();
//});

//Route::get('/nbsMarket', function () {
//    $response = \Illuminate\Support\Facades\Http::get(config('api.API_AUTH').'/api/users');
//    return  \Illuminate\Support\Facades\Response::json($response->json(),$response->status());
//});

