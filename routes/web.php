<?php

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

Route::get('/', function() {
	return view('welcome');
});

Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');

Route::get('/about', 'HomeController@about');

Route::get('/dashboard', 'BudgetController@index') -> name('dashboard');
Route::post('/budget', 'BudgetController@store');
Route::post('/budget/update/{budget}', 'BudgetController@update');
Route::get('/budget/{budget}', 'BudgetController@show') -> name('budget');
Route::delete('/budget/{budget}', 'BudgetController@destroy') -> name('delete');

Route::get('/categories', 'CategoryController@index') -> name('categories');
Route::post('/category', 'CategoryController@store');
Route::post('/category/update/{category}', 'CategoryController@update');
Route::delete('/category/{category}', 'CategoryController@destroy');

Route::post('/cashflow', 'CashflowController@store') -> name('cashflow');
Route::post('/cashflow/update/{cashflow}', 'CashflowController@update');
Route::delete('/cashflow/{cashflow}', 'CashflowController@destroy');

Route::post('/report', 'BudgetReportController@sendSummary') -> name('report');

Route::get('auth/google', 'Auth\GoogleController@redirectToGoogle')->name('google');
Route::get('auth/google/callback', 'Auth\GoogleController@handleGoogleCallback');