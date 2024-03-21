<?php

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

/*
|--------------------------------------------------------------------------
| Route Patterns
|--------------------------------------------------------------------------
| Constraints for route parameters with a specific name
|
*/

Route::pattern('id', '[0-9]+');


Route::namespace('Api')->name('api.')->group(function () {

    // token protected routes
    Route::namespace('V1')->prefix('v1')->name('v1.')->middleware(['auth:api', 'log-active-api'])->group(function () {
        Route::prefix('organization')->name('organization.')->group(function () {
            Route::get('/', 'OrganizationController@list')->name('list');
            Route::get('/{id}', 'OrganizationController@show')->name('show');
            Route::get('/{id}/data', 'OrganizationController@listData')->name('data');
            Route::post('/{id}/data', 'OrganizationController@postData')->name('data.post');
            Route::get('/data/{id}', 'OrganizationController@showData')->name('data.show');
            Route::delete('/data/{id}', 'OrganizationController@deleteData')->name('data.delete');
        });

        Route::prefix('post')->name('post.')->group(function () {
            Route::get('/', 'PostController@list')->name('list');
            Route::get('/{id}', 'PostController@show')->name('show');
            Route::post('/', 'PostController@post')->name('post');
            Route::get('/{id}/data', 'PostController@listData')->name('data');
            Route::post('/{id}/data', 'PostController@postData')->name('data.post');
            Route::delete('/data/{id}', 'PostController@deleteData')->name('data.delete');
        });

        Route::prefix('channel-index')->name('channel-index.')->group(function () {
            Route::get('/', 'ChannelIndexController@list')->name('list');
        });

        Route::prefix('period')->name('period.')->group(function () {
            Route::get('/', 'PeriodController@list')->name('list');
            Route::get('/current', 'PeriodController@getCurrent')->name('current');
        });

        Route::prefix('channel')->name('channel.')->group(function () {
            Route::get('/', 'ChannelController@list')->name('list');
        });

        Route::prefix('log')->name('log.')->group(function () {
            Route::post('/', 'LogController@post')->name('post');
        });
    });
});

Route::fallback(function () {
    return response()->json(['message' => 'Page Not Found.'], 404);
});
