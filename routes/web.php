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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*- portal -*/
Route::prefix('portal')->group(function(){
    /*-- manager --*/
    Route::prefix('manager')->group(function(){
        /*--- authentication  ---*/
        Route::get('/', 'ManagerLoginController@index')->name('manager.login');
        Route::post('/', 'ManagerLoginController@processManagerLogin')->name('manager.process.login');
        Route::get('/logout', 'ManagerLoginController@logout')->name('manager.logout');

        /*--- dashboard ---*/
        Route::get('/dashboard', 'ManagerPagesController@showDashboard')->name('manager.dashboard');

        /*--- users ---*/
        Route::get('/users', 'ManagerPagesController@showUsers')->name('manager.show.users');
        Route::post('/users', 'ManagerPagesController@processUsers')->name('manager.process.users');
        Route::get('/user/{user_id}', 'ManagerPagesController@showUser')->name('manager.show.user');
        Route::post('/user/{user_id}', 'ManagerPagesController@processUser')->name('manager.process.user');
        Route::get('/user/add', 'ManagerPagesController@showUser')->name('manager.show.add.user');
        Route::post('/user/add', 'ManagerPagesController@processUser')->name('manager.process.add.user');

        /*--- products ---*/
        Route::get('/products', 'ManagerPagesController@showProducts')->name('manager.show.products');
        Route::post('/products', 'ManagerPagesController@processProducts')->name('manager.process.products');
        Route::get('/product/{product_id}', 'ManagerPagesController@showProduct')->name('manager.show.product');
        Route::post('/product/{product_id}', 'ManagerPagesController@processProduct')->name('manager.process.product');
        Route::get('/product/add', 'ManagerPagesController@showProduct')->name('manager.show.add.product');
        Route::post('/product/add', 'ManagerPagesController@processProduct')->name('manager.process.add.product');

        /*--- categories ---*/
        Route::get('/categories', 'ManagerPagesController@showCategories')->name('manager.show.categories');
        Route::post('/categories', 'ManagerPagesController@processCategories')->name('manager.process.categories');
        Route::get('/category/{category_id}', 'ManagerPagesController@showCategory')->name('manager.show.category');
        Route::post('/category/{category_id}', 'ManagerPagesController@processCategory')->name('manager.process.category');
        Route::get('/category/add', 'ManagerPagesController@showCategory')->name('manager.show.add.category');
        Route::post('/category/add', 'ManagerPagesController@processCategory')->name('manager.process.add.category');

        /*--- activity log ---*/
        Route::get('/settings', 'ManagerPagesController@showSettings')->name('manager.show.settings');
        Route::post('/settings', 'ManagerPagesController@showSettings')->name('manager.process.settings');

        /*--- activity log ---*/
        Route::get('/activity-log', 'ManagerPagesController@showActivityLog')->name('manager.show.activity-log');

    });
});

