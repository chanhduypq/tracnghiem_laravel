<?php

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JP_COMMUNITY\Models\Customer;
use JP_COMMUNITY\Models\Client;
use JP_COMMUNITY\Models\Like;
use JP_COMMUNITY\Models\Report;
use JP_COMMUNITY\Models\User;
use JP_COMMUNITY\Models\UserReview;

class UserController extends BaseController
{

    protected $checkbox = ['sex'];
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware('auth')->only(['create','edit', 'destroy']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('layouts.app');
    }

    /**
     * Load data for popup modal
     */
    public function ajaxModalProfile(Request $request) {
        $this->validate($request, [
            'user_id' => 'required|integer',
        ]);
        $res = User::withoutGlobalScopes()->where('id', $request->get('user_id'))->first();

        switch ($res->user_type) {
            case 'is_client':
                $res->with('client');
                break;
            case 'is_customer':
                $res->with('customer');
                break;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $res->id,
                'name' => $res->full_name,
                'user_rating_for' => $res->user_rating_for,
                'user_type' => $res->user_type,
                'txt_total_rating_for' => trans('message.total_rating_for', ['t' => count($res->reviewed_of)]),
                'email' => $res->email,
                'address' => $res->address,
            ]
        ]);
    }

    /**
     * Profile of user
     * @param $user_id
     * @return mixed
     */
    public function profile( $user_id ) {
        $users = User::withOutGlobalScope('active')->find($user_id);

        $videoPath = null;
        $imagePath = null;
        $usersReview = null;
        $ratingOfUser = null;
        $clientNames = null;
        if ($users->user_type == 'is_customer') {
            $users->load('customer');
            $videoPath = !empty($users->customer) ? $users->customer->video : null;
            $imagePath = !empty($users->customer) ? User::$folderImageUpload.$users->customer->image : null;
            $usersReview = UserReview::where('target_id', $user_id)->where('target_type', REVIEW_CUSTOMER_REVIEW_TYPE)->paginate(5);
            $usersReview->load('user')->load('client');
//            $ratingOfUser = User::getRatingOfCustomer($users->id);
        }

        if ($users->user_type == 'is_client') {
            $users->load('client');
        }

        $userFullNames = User::usersFullName();

        $isOwner = User::isOwner($user_id);

        $usersCommentedL1 = $users->user_comment_for()
                                ->where('target_type', COMMENT_CUSTOMER_COMMENT_TYPE)
                                ->whereNull('grand_parent_id')
                                ->orderBy('created_at', 'DESC')->paginate(COMMENT_LIMIT);

        $usersCommentedBuff = collect($usersCommentedL1)->all();
        $usersCommented = $this->buildResponseComments(collect($usersCommentedL1)->get('data'), COMMENT_CUSTOMER_COMMENT_TYPE);

        $usersCommentedBuff['data'] = $usersCommented;
        $usersCommented = $usersCommentedBuff;

        $countUsersReviewed = User::getNumberReviewedOfUsers(REVIEW_CUSTOMER_REVIEW_TYPE);
        $countUsersLiked = User::getNumberlikedOfUsers(LIKE_CUSTOMER_LIKE_TYPE);
        $resLocations = DB::table('locations')->get();

        $curUserReviewed = UserReview::where('target_id', $user_id)->where('target_type', REVIEW_CUSTOMER_REVIEW_TYPE)->get();
        $idsReviewed = $curUserReviewed->pluck('review_id')->all();

        $userLikeReviewedBuff = Like::where('target_type', LIKE_CUSTOMER_REVIEWED_TYPE)
            ->whereIn('target_id', $idsReviewed)->where('value', 1)->get();

        $userLikeReviewed = [];
        foreach ($userLikeReviewedBuff as $liked) {
            $userLikeReviewed[$liked->target_id][] = $liked->user_like_id;
        }

        $curUserReported = [];
        if (Auth::check()) {
            $curUserReported = Report::where('user_report_id', Auth::id())->where('target_type', REPORT_CUSTOMER_REVIEWED_TYPE)->pluck('value', 'target_id')->all();
        }


        $userReviewedOf = UserReview::where('target_type', REVIEW_CUSTOMER_REVIEW_TYPE)
            ->where('user_review_id', $user_id)->get();
        $userReviewedOf = collect($userReviewedOf)->groupBy('target_id');

        $cities = [];
        foreach ($resLocations as $location) {
            if (!empty($location->country_code)) {
                $cities[$location->country_code][$location->location_id] = $location->city_name;
            }
        }
        $userLiked = collect($users->liked_for)->pluck('value', 'user_like_id');

        return view('users.profiles', compact('users', 'userLiked', 'userLikeReviewed','cities', 'isOwner', 'videoPath', 'imagePath', 'usersReview', 'userReviewedOf', 'userFullNames', 'usersCommented', 'countUsersReviewed', 'countUsersLiked', 'curUserReported'));
    }
    /**
     * Client can review and vote start for a customer
     * @param int $user_id
     */
    public function review() {
        return view('users.review');
    }
    /**
     * Function update profile
     */
    public function update_profile(Request $request) {

        if (User::isUserType('is_customer')) {
            return $this->update_customer_profile($request);
        }
        if (User::isUserType('is_client')) {
            return $this->update_client_profile($request);
        }

    }

    private function update_client_profile($request) {

        $this->validate($request, [
            'user_id' => 'required|integer',
            'first_name' => 'max:255',
            'last_name' => 'max:255',
            'birthday' => 'date_format:Y-m-d',
            'phone' => 'string|max:12',
            'sex' => 'boolean',
            'description' => 'string',
            'country' => 'string|in:vn,ja',
            'city' => 'integer',
            'state' => 'string|min:3|max:255',
            'street_number' => 'string|min:3|max:255',
            'street_name' => 'string|min:3|max:255',
            'postal_code' => 'string|max:12',
            'avatar' => 'image'
        ]);

        $data_post = $request->all();
        $res = Client::where('user_id', $data_post['user_id'])->first();

        $image_name = '';
        if ($request->hasFile('avatar')) {
            $uploadImageSetting = User::$uploadImageSetting;
            $folderImageUpload = User::$folderImageUpload;
            $image_name = ImageController::upload($request->file('avatar'), $uploadImageSetting, $folderImageUpload);
        }

        if (empty($res)) {
            $client = new Client([
                'user_id' => $data_post['user_id'],
                'first_name' => isset($data_post['first_name']) ? $data_post['first_name'] : null,
                'last_name' => isset($data_post['last_name']) ? $data_post['last_name'] : null,
                'birthday' => !empty($data_post['birthday']) ? $data_post['birthday'] : null,
                'sex' => isset($data_post['sex']) ? $data_post['sex'] : null,
                'phone' => isset($data_post['phone']) ? $data_post['phone'] : null,
                'street_number' => isset($data_post['street_number']) ? $data_post['street_number'] : null,
                'street_name' => isset($data_post['street_name']) ? $data_post['street_name'] : null,
                'city' => isset($data_post['city']) ? $data_post['city'] : null,
                'state' => isset($data_post['state']) ? $data_post['state'] : null,
                'postal_code' => isset($data_post['postal_code']) ? $data_post['postal_code'] : null,
                'country' => isset($data_post['country']) ? $data_post['country'] : null,
                'description' => isset($data_post['description']) ? $data_post['description'] : null,
                'avatar' => $image_name
            ]);
            $client->save();
        } else {
            $res->update([
                'first_name' => isset($data_post['first_name']) ? $data_post['first_name'] : $res->first_name,
                'last_name' => isset($data_post['last_name']) ? $data_post['last_name'] : $res->last_name,
                'birthday' => !empty($data_post['birthday']) ? $data_post['birthday'] : null,
                'sex' => isset($data_post['sex']) ? $data_post['sex'] : $res->sex,
                'phone' => isset($data_post['phone']) ? $data_post['phone'] : $res->phone,
                'street_number' => isset($data_post['street_number']) ? $data_post['street_number'] : $res->street_number,
                'street_name' => isset($data_post['street_name']) ? $data_post['street_name'] : $res->street_name,
                'city' => isset($data_post['city']) ? $data_post['city'] : $res->city,
                'state' => isset($data_post['state']) ? $data_post['state'] : $res->state,
                'postal_code' => isset($data_post['postal_code']) ? $data_post['postal_code'] : $res->postal_code,
                'country' => isset($data_post['country']) ? $data_post['country'] : $res->country,
                'description' => isset($data_post['description']) ? $data_post['description'] : $res->description,
                'avatar' => !empty($image_name) ? $image_name : $res->avatar,
            ]);
        }

        return \Redirect::route('profile', $data_post['user_id'])->with('message-success', 'Success');
    }

    private function update_customer_profile($request) {

        $this->validate($request, [
            'user_id' => 'required|integer',
            'customer_name' => 'max:255',
            'foundation_date' => 'date_format:Y-m-d',
            'video' => 'string',
            'image' => 'image',
            'phone' => 'string|max:12',
            'fax' => 'min:6',
            'description' => 'string',
            'detail' => 'string',
            'country' => 'string|in:vn,ja',
            'city' => 'integer',
            'state' => 'string|min:3|max:255',
            'street_number' => 'string|min:3|max:255',
            'street_name' => 'string|min:3|max:255',
            'postal_code' => 'string|max:12',
            'website' => 'string',
        ]);

        $data_post = $request->all();
        $res = Customer::where('user_id', $data_post['user_id'])->first();

        $image_name = '';
        if ($request->hasFile('image')) {
            $uploadImageSetting = User::$uploadImageSetting;
            $folderImageUpload = User::$folderImageUpload;
            $image_name = ImageController::upload($request->file('image'), $uploadImageSetting, $folderImageUpload);
        }

        /*if ($request->hasFile('video')) {
            $videos = new VideoController();
            $uploadVideoFolder =  User::$folderVideoUpload;
            $resUploadVideo = $videos->uploadMaster($request->file('video'), $uploadVideoFolder, 'video');
        }*/

        if (empty($res)) {
            $customer = new Customer([
                'user_id' => $data_post['user_id'],
                'customer_name' => isset($data_post['customer_name']) ? $data_post['customer_name'] : null,
                'foundation_date' => !empty($data_post['foundation_date']) ? $data_post['foundation_date'] : null,
                'fax' => isset($data_post['fax']) ? $data_post['fax'] : null,
                'phone' => isset($data_post['phone']) ? $data_post['phone'] : null,
                'website' => isset($data_post['website']) ? $data_post['website'] : null,
                'street_number' => isset($data_post['street_number']) ? $data_post['street_number'] : null,
                'street_name' => isset($data_post['street_name']) ? $data_post['street_name'] : null,
                'city' => isset($data_post['city']) ? $data_post['city'] : null,
                'state' => isset($data_post['state']) ? $data_post['state'] : null,
                'postal_code' => isset($data_post['postal_code']) ? $data_post['postal_code'] : null,
                'country' => isset($data_post['country']) ? $data_post['country'] : null,
                'description' => isset($data_post['description']) ? $data_post['description'] : null,
                'detail' => isset($data_post['detail']) ? $data_post['detail'] : null,
                'video' => !empty($data_post['video']) ? $data_post['video'] : null,
                'image' => $image_name,
            ]);
            $customer->save();
        } else {
            $res->update([
                'customer_name' => isset($data_post['customer_name']) ? $data_post['customer_name'] : null,
                'foundation_date' => !empty($data_post['foundation_date']) ? $data_post['foundation_date'] : null,
                'fax' => isset($data_post['fax']) ? $data_post['fax'] : $res->fax,
                'phone' => isset($data_post['phone']) ? $data_post['phone'] : null,
                'website' => isset($data_post['website']) ? $data_post['website'] : null,
                'street_number' => isset($data_post['street_number']) ? $data_post['street_number'] : $res->street_number,
                'street_name' => isset($data_post['street_name']) ? $data_post['street_name'] : $res->street_name,
                'city' => isset($data_post['city']) ? $data_post['city'] : $res->city,
                'state' => isset($data_post['state']) ? $data_post['state'] : $res->state,
                'postal_code' => isset($data_post['postal_code']) ? $data_post['postal_code'] : $res->postal_code,
                'country' => isset($data_post['country']) ? $data_post['country'] : $res->country,
                'description' => isset($data_post['description']) ? $data_post['description'] : $res->description,
                'detail' => isset($data_post['detail']) ? $data_post['detail'] : $res->detail,
                'video' => !empty($data_post['video']) ? $data_post['video'] : $res->video,
                'image' => !empty($image_name) ? $image_name : $res->image,
            ]);
        }

        return \Redirect::route('profile', $data_post['user_id'])->with('message-success', 'Success');
    }

    public function switchLang($lang) {
        if (array_key_exists($lang, \Config::get('app.locales'))) {
            \Session::set('myLocale', $lang);
        }
        return back();
    }
}
