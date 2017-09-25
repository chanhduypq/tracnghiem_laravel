<?php

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'admin_groups']], function () {

    Route::get('/', function () {
        return view('admins.index');
    })->name('admin');

    Route::get('/address', [
        'uses' => 'Admin\AddressController@index',
        'as' => 'admin.address.index'
    ]);

    Route::get('/save_address', [
        'uses' => 'Admin\AddressController@save',
        'as' => 'admin.address.save'
    ]);

    Route::get('/delete_address', [
        'uses' => 'Admin\AddressController@delete',
        'as' => 'admin.address.save'
    ]);

    Route::get('slider', [
        'uses' => 'Admin\SliderController@index',
        'as' => 'admin.slider.index'
    ]);

    Route::resource('users', 'Admin\UserController',
        [
            'names' => [
                'index' => 'admin.users.index',
                'create' => 'admin.users.create',
                'store' => 'admin.users.store',
                'edit' => 'admin.users.edit',
                'destroy' => 'admin.users.delete',
                'update' => 'admin.users.update',
            ]
        ]
    );

    Route::get('admin/users/delete', 'Admin\UserController@destroy');

    Route::resource('categories', 'Admin\CategoryController',
        [
            'names' => [
                'index' => 'admin.categories.index',
                'create' => 'admin.categories.create',
            ]
        ]
    );

    Route::get('news/delete', 'Admin\NewsController@destroy');
    Route::resource('news', 'Admin\NewsController',
        [
            'names' => [
                'index' => 'admin.news.index',
                'create' => 'admin.news.create',
                'store' => 'admin.news.store',
                'edit' => 'admin.news.edit',
                'destroy' => 'admin.news.delete',
                'update' => 'admin.news.update',
                'show' => 'admin.news.show',
            ]
        ]
    );
});