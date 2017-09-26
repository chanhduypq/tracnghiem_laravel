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

@include ('_youtube.php');

Route::get('/local_change/{lang}', [
    'uses' => 'UserController@switchLang',
    'as' => 'lang.switch'
]);

Route::any('/',
    [
        'uses' => 'IndexController@index',
        'as' => '/'
    ]);
Route::any('/index/logout',
    [
        'uses' => 'IndexController@logout',
        'as' => 'index_logout'
    ]);
Route::any('/index/login',
    [
        'uses' => 'IndexController@login',
        'as' => 'index_login'
    ]);
Route::any('/guide',
    [
        'uses' => 'IndexController@guide',
        'as' => 'index_guide'
    ]);
Route::any('/thi',
    [
        'uses' => 'ThiController@index',
        'as' => 'thi'
    ]);//->middleware('auth.basic');//->middleware('auth');
Route::any('/thi/viewresult',
    [
        'uses' => 'ThiController@viewresult',
        'as' => 'thi_viewresult'
    ]);
Route::any('/review',
    [
        'uses' => 'ReviewController@index',
        'as' => 'review'
    ]);
Route::any('/review/viewresult',
    [
        'uses' => 'ReviewController@viewresult',
        'as' => 'review_viewresult'
    ]);
Route::any('/question/{nganhNgheId}/{level}',
    [
        'uses' => 'QuestionController@index',
        'as' => 'question'
    ]);
Route::any('/admin/index/ajaxchangepassword',
    [
        'uses' => 'AdminIndexController@ajaxchangepassword',
        'as' => 'admin_index_ajaxchangepassword'
    ]);


Route::get('user/{id}/profile', [
    'uses' => 'UserController@profile',
    'as' => 'profile'
]);
Route::get('/load_modal_profile', [
   'uses' => 'UserController@ajaxModalProfile',
    'as' => 'user.modal.profile'
]);
Route::get('/social_login', 'Auth\SocialController@login');

Route::get('/get_location', 'Admin\AddressController@get_location');

Route::get('/like', 'LikeController@like');
Route::get('/report', 'ReportController@report');
Route::get('/comment', 'CommentController@addComment');
Route::get('/more_comment', 'CommentController@moreComment');
Route::get('/get_comments', 'CommentController@getComments');
Route::get('/edit_comment', 'CommentController@updateComment');
Route::get('/delete_comment', 'CommentController@deleteComment');
Route::get('/send_message', 'MessageController@send');
Route::get('/message_detail', 'MessageController@message_detail');
Route::get('/get_catalog', 'LogsController@getCatalog');

Route::get('user/{id}/news', [
    'uses' =>'NewsController@index',
    'as' => 'user.news.index'
]);
Route::get('user/{id}/news/{news_id}', [
    'uses' =>'NewsController@show',
    'as' => 'user.news.detail'
]);

Route::get('page/about_japan', [
    'uses' => 'PageController@index',
    'as' => 'page.about_japan'
]);

Route::get('/japanese_schools', [
    'uses' => 'PageController@index',
    'as' => 'page.school_japan'
]);

Route::get('page/study_japan', [
    'uses' => 'PageController@index',
    'as' => 'page.study_japan'
]);

Route::get('page/job_japan', [
    'uses' => 'PageController@index',
    'as' => 'page.job_japan'
]);
Route::get('page/connection_info', [
    'uses' => 'PageController@index',
    'as' => 'page.connection_info'
]);

Route::group(['middleware' => 'auth'], function () {

    Route::post('upload_image_editor', [
        'uses' => 'ImageController@uploadImageCkEditor',
        'as' => 'uploadImageCkEditor'
    ]);

    Route::post('upload_image_editor_news', [
        'uses' => 'ImageController@uploadImageCkEditor',
        'as' => 'uploadImageCkEditorNews'
    ]);

    Route::get('user/{id}/review', [
        'uses' => 'UserReviewController@create',
        'as' => 'user_review'
    ]);

    Route::post('user/{id}/review', [
        'uses' => 'UserReviewController@create',
        'as' => 'user_review'
    ]);

    Route::post('user/{id}/create_review', [
        'uses' => 'UserReviewController@store',
        'as' => 'user.review.create'
    ]);

    Route::resource('news', 'NewsController');

    Route::post('user/{id}/update_profile', [
        'uses' => 'UserController@update_profile',
        'as' => 'update_profile'
    ]);
    Route::get('user/{id}/message', [
        'uses' => 'MessageController@index',
        'as' => 'user.message.index'
    ]);
    Route::get('user/{id}/message/{message_id}', [
        'uses' => 'MessageController@show',
        'as' => 'user.message.show'
    ]);
/* Admin page*/
    @require_once('admin.php');
//    Route::resource('profile/{id}', 'ProfileController');
});

Route::get('news/{id}', [
        'uses' => 'NewsController@show',
        'as' => 'news.show']
);

Route::get('news/{id}/destroy', [
        'uses' => 'NewsController@destroy',
        'as' => 'news.destroy']
);

Route::get('logout', 'Auth\LoginController@logout');
Auth::routes();

