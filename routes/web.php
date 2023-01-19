<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'Auth\AdminLoginController@index')->name('login');
Route::post('/admin/login/submit', 'Auth\AdminLoginController@login_submit');
Route::post('/admin/register/submit', 'Auth\AdminLoginController@register');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/getUser', 'Auth\AdminLoginController@getUser');
Route::post('/user/update/profile/{id}', 'Auth\AdminLoginController@updateProfile');

Route::middleware(['auth'])->group(function () {
    Route::resource('product','ProductController');
    Route::get('/', function () {
        return view('app');
    });

    Route::get('outstock','ProductController@outstock');
    Route::post('sell/store','SellController@store');
    Route::post('sell/report','SellController@getSell');
    Route::delete('sell/{id}','SellController@destroy');
    Route::get('search/product/{field}/{query}','ProductController@search');
    Route::get('search/sell/{field}/{query}','SellController@search');
    Route::get('sell/stats','SellController@sell_stats');
    Route::get('{any}', function () {
        return view('app');
    })->where('any','.*');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
