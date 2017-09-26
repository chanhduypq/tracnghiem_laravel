<?php

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Common\Download;

class IndexController extends BaseController {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $item = DB::table('home_content')->first();
        $content = $item['content'];
        $bg = $item['bg'];

        return view('index.index', compact(['content', 'bg']));
    }

    public function guide() {
        Download::download(UPLOAD . "guide/");        
    }

    public function login(Request $request) {
        $username = $request->get('username');
        $password = $request->get('password');
        if ($username) {
            $user = DB::table('user')->where('email', $username)->where('password', sha1($password))->first();
            if (is_array($user) && count($user) > 0) {
                if ($user['is_admin'] == '1') {
                    $user['user'] = 'admin';
                }
                Session::set('user', $user);
                echo '';
            } else {
                echo 'error';
            }
        }
        return;
    }

    public function logout() {
        Session::set('user', null);
        return redirect()->action('IndexController@index');
    }

    public function logoutajax() {
        Session::set('user', null);
        exit;
    }

}
