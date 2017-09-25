<?php

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JP_COMMUNITY\Models\Like;
use JP_COMMUNITY\Models\News;
use JP_COMMUNITY\Models\User;
use JP_COMMUNITY\Models\UserReview;
use JP_COMMUNITY\Models\Client;

class SearchController extends BaseController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $postData = $request->all();

        $this->validate($request, [
            'school_name' => 'string',
            'address' => 'array'
        ]);
        $users = User::where('user_type', 'is_customer')->get();

        $users->load([
            'customer' => function($query) use ($postData) {
                if (!empty($postData['school_name'])) {
                    $query->where('customer_name', 'LIKE', "%".$postData['school_name']."%");
                }
                if (!empty($postData['address'])) {
                    $query->whereIn('country', $postData['address']);
                }
            }
        ]);

        $currentUserLikedOfs = Like::where('user_like_id', Auth::id())
                                ->where('target_type', LIKE_CUSTOMER_LIKE_TYPE)
                                ->where('value', 1)->get();
        $userLiked = collect($currentUserLikedOfs)->pluck('value', 'target_id');

        $countUsersReviewed = User::getNumberReviewedOfUsers(REVIEW_CUSTOMER_REVIEW_TYPE);
        $countUsersLiked = User::getNumberlikedOfUsers(LIKE_CUSTOMER_LIKE_TYPE);
        $countLikedForCustomer = User::getNumberLikedFor(LIKE_CUSTOMER_LIKE_TYPE);
        $countCommentedForCustomer = User::getNumberCommentFor(COMMENT_CUSTOMER_COMMENT_TYPE);
        $news = News::where('active', 1)->with('like')->paginate(HOME_NEWS_LIMIT);
        return view('searchs.index', compact(['users', 'userLiked', 'countUsersReviewed', 'countUsersLiked', 'news', 'countLikedForCustomer', 'countCommentedForCustomer']))
            ->withInput($request->all());
    }
}
