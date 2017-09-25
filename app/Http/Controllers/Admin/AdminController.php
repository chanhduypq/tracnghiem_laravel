<?php
namespace JP_COMMUNITY\Http\Controllers\Admin;

use JP_COMMUNITY\Http\Controllers\Controller;

class AdminController extends Controller
{

    public function __construct()
    {

    }

    /**
     * List of admin user
     */
    public function index() {
        return view('admins.index');
    }

    /**
     * Detail of user
     * @param int $id
     */
    public function show($id) {

    }

}