<?php

namespace JP_COMMUNITY\Http\Controllers;

use JP_COMMUNITY\Models\News;
use JP_COMMUNITY\Models\User;

class PageController extends BaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index() {

        $news = new News();
        $request = Request();
        $newsImagePath = News::$uploadSetting;

        $cat = '';
        $users = [];
        if ($request->is('page/about_japan')) {
            $cat = PAGE_ABOUT_JAPAN;
        } elseif ($request->is('page/study_japan')) {
            $cat = PAGE_STUDY_IN_JAPAN;
        } elseif ($request->is('page/job_japan')) {
            $cat = PAGE_JOB_IN_JAPAN;
        } elseif ($request->is('page/school_japan') || $request->is('japanese_schools')) {
            $cat = PAGE_SCHOOL_JAPANESE;

            $users = User::with(['customer' => function($query) use ($request) {

                if (!empty($request->get('country_code')) && empty($request->get('city_code'))) {
                    $query->where('country', $request->get('country_code'));
                }

                if (!empty($request->get('country_code')) && !empty($request->get('city_code'))) {
                    $query->where('country', $request->get('country_code'))
                        ->where('city', $request->get('city_code'));
                }

            }])->get();
        }

        $news = $news->where('target_page', 'like', "%$cat%")->get();

        return view('pages.index', compact('news', 'users', 'newsImagePath'));
    }

}