<?php
// Youtube upload
Route::group(['prefix' => 'youtube'], function() {

    /**
     * Authentication
     */
    Route::get(config('youtube.routes.authentication_uri'), function()
    {
        return redirect()->to(Youtube::createAuthUrl());
    });

    /**
     * Redirect
     */
    Route::get('uploads', function(Illuminate\Http\Request $request)
    {
        $code = $request->get('code');
        if(is_null($code)) {
            throw new Exception('$_GET[\'code\'] is not set.');
        } else {
//            $youtube = new \Youtube();
            $youtube = new \JP_COMMUNITY\Http\Controllers\YoutubeUploadController();

            $accessToken = $youtube->handleGetAccessToken($request->all());
        }

        return redirect('/');
    });
    Route::get('ajax_save', 'YoutubeUploadController@ajax_save');

});
// End youtube upload