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



/*- portal -*/
Route::prefix('portal')->group(function(){
    /*-- manager --*/
    Route::prefix('manager')->group(function(){
        /*--- authentication  ---*/
        Route::get('/', 'Auth\ManagerLoginController@index')->name('manager.login');
        Route::post('/', 'Auth\ManagerLoginController@login')->name('manager.process.login');
        Route::get('/logout', 'Auth\ManagerLoginController@logout')->name('manager.logout');

        /*--- dashboard ---*/
        Route::get('/dashboard', 'ManagerPagesController@showDashboard')->name('manager.dashboard');

        /*--- users ---*/
        Route::get('/users', 'ManagerPagesController@showUsers')->name('manager.show.users');
        Route::post('/users', 'ManagerPagesController@processUsers')->name('manager.process.users');
        Route::get('/user/add', 'ManagerPagesController@showAddUser')->name('manager.show.add.user');
        Route::post('/user/add', 'ManagerPagesController@processAddUser')->name('manager.process.add.user');
        Route::get('/user/{user_id}', 'ManagerPagesController@showUser')->name('manager.show.user');
        Route::post('/user/{user_id}', 'ManagerPagesController@processUser')->name('manager.process.user');

        /*--- products ---*/
        Route::get('/products', 'ManagerPagesController@showProducts')->name('manager.show.products');
        Route::post('/products', 'ManagerPagesController@processProducts')->name('manager.process.products');
        Route::get('/product/add', 'ManagerPagesController@showAddProduct')->name('manager.show.add.product');
        Route::post('/product/add', 'ManagerPagesController@processAddProduct')->name('manager.process.add.product');
        Route::get('/product/{product_slug}', 'ManagerPagesController@showProduct')->name('manager.show.product');
        Route::post('/product/{product_slug}', 'ManagerPagesController@processProduct')->name('manager.process.product');

        /*--- categories ---*/
        Route::get('/categories', 'ManagerPagesController@showCategories')->name('manager.show.categories');
        Route::post('/categories', 'ManagerPagesController@processCategories')->name('manager.process.categories');
        Route::get('/category/add', 'ManagerPagesController@showAddCategory')->name('manager.show.add.category');
        Route::post('/category/add', 'ManagerPagesController@processAddCategory')->name('manager.process.add.category');
        Route::get('/category/{category_slug}', 'ManagerPagesController@showCategory')->name('manager.show.category');
        Route::post('/category/{category_slug}', 'ManagerPagesController@processCategory')->name('manager.process.category');

        /*--- site settings ---*/
        Route::get('/settings', 'ManagerPagesController@showSettings')->name('manager.show.settings');
        Route::post('/settings', 'ManagerPagesController@processSettings')->name('manager.process.settings');

        /*--- account settings ---*/
        Route::get('/account', 'ManagerPagesController@showAccount')->name('manager.show.account');
        Route::post('/account', 'ManagerPagesController@processAccount')->name('manager.process.account');

        /*--- activity log ---*/
        Route::get('/activity-log', 'ManagerPagesController@showActivityLog')->name('manager.show.activity-log');
        Route::post('/activity-log', 'ManagerPagesController@processActivityLog')->name('manager.process.activity-log');

    });
});

/*- main -*/
Route::get('/shop/{product_slug}', 'MainPagesController@showProduct')->name('product');
Route::get('/shop/category/{category_slug}', 'MainPagesController@showCategory')->name('category');
Route::get('/shop', 'MainPagesController@showShop')->name('shop');
Route::post('/shop', 'MainPagesController@filterShop')->name('shop.filter');
Route::post('/about-us', 'MainPagesController@showAboutUs')->name('about-us');
Route::post('/contact-us', 'MainPagesController@showContactUs')->name('contact-us');
Route::post('/locate-a-store', 'MainPagesController@showLocateAStore')->name('locate-a-store');
Route::get('/', 'MainPagesController@home')->name('home');


