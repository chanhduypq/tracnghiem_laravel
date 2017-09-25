<?php
namespace JP_COMMUNITY\Http\Controllers\Admin;

use JP_COMMUNITY\Http\Controllers\BaseController;

class SliderController extends BaseController
{

    public function __construct()
    {

    }

    /**
     * List of admin user
     */
    public function index() {
        return view('admins.sliders.index');
    }

    /**
     * Detail of user
     * @param int $id
     */
    public function show($id) {

    }

}