<?php

namespace JP_COMMUNITY\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends BaseModel implements AuthenticatableContract, CanResetPasswordContract, AuthorizableContract
{
    use Authenticatable, Authorizable, CanResetPassword;
    use Notifiable;
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'users';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'user_type', 'active', 'full_name'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function userType (){
        // TODO: Temporary define user_type here, should update to get from data base.
        return [
            'admin' => [
                'is_admin' => trans('global.is_admin'),
                'is_staff' => trans('global.is_staff'),
                'is_customer' => trans('global.is_customer'),
                'is_client' => trans('global.is_client')
            ],
            'client'=> [
                'is_customer' => trans('global.is_customer'),
                'is_client' => trans('global.is_client')
            ]
        ];
    }

    public static $uploadImageSetting = [
        [
            'path' => '/uploads/users/profile/large/',
            'width' => 500,
            'height' => 500,
        ],
        [
            'path' => '/uploads/users/profile/medium/',
            'width' => 300,
            'height' => 300,
        ],
        [
            'path' => '/uploads/users/profile/small/',
            'width' => 100,
            'height' => 100,
        ],
    ];

    public static $folderImageUpload = '/uploads/users/profile/';

    public static $folderVideoUpload = '/uploads/videos/users/profile/';

    public function getIsOwnerAttribute() {
        return $this->id === Auth::id();
    }

    public static function isOwner($user_id) {

        if (empty($user_id) || empty(Auth::id())) {
            return false;
        }

        if ($user_id == Auth::id()) {
            return true;
        }

        return false;
    }

    /**
     * Start scope
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('active', function (Builder $builder){
            $builder->where('active', 1);
        });
    }

    /**
     * Note: Don't use this Accessor in loop
     */
    public function getClientNameAttribute() {
        return !empty($this->client) ? $this->client->full_name : null;
    }

    /**
     * Note: Don't use this Accessor in loop
     */
    public function getCustomerNameAttribute() {
        return !empty($this->customer) ? $this->customer->customer_name : null;
    }

    public function getFullNameAttribute() {
        if (!empty($this->client) && $this->user_type == 'is_client') {
            return $this->client->full_name;
        }
        if (!empty($this->customer) && $this->user_type == 'is_customer') {
            return $this->customer->customer_name;
        }
        return $this->name;
    }

    public function getAddressAttribute() {
        if (!empty($this->client) && $this->user_type == 'is_client') {
            return $this->client->address;
        }
        if (!empty($this->customer) && $this->user_type == 'is_customer') {
            return $this->customer->address;
        }

    }

    public function getFullPathImageAttribute() {

        if (!empty($this->client) && $this->user_type == 'is_client') {
            return $this->client->full_path_avatar;
        }
        if (!empty($this->customer) && $this->user_type == 'is_customer') {
            return $this->customer->full_path_image;
        }
    }

    static function usersFullName($userIds = null) {
        return [];
        $res = [];
        $res2 = [];

        if (empty($userIds)) {
            $sql = 'SELECT u.id as user_id, customer_name as full_name FROM users AS u'.
                    ' LEFT JOIN customer as c'.
                    ' ON u.id = c.user_id '.
                    ' WHERE u.user_type = "is_customer"'.
                    ' AND u.deleted_at is null'.
                    ' union ('.
                        'SELECT u.id as user_id, concat(c.first_name,  " ", c.last_name) AS full_name FROM users AS u'.
                        ' LEFT JOIN client as c'.
                        ' ON u.id = c.user_id '.
                        ' WHERE u.user_type = "is_client"'.
                        ' AND u.deleted_at is null'.
                    ')';
            $res = array_pluck(DB::select($sql), 'full_name', 'user_id');

            $sql2 = 'SELECT id AS user_id, name AS full_name FROM users WHERE id NOT IN'.
	                '('.
                        'SELECT user_id FROM customer WHERE deleted_at IS NULL'.
                        ' UNION'.
                        ' SELECT user_id FROM client WHERE deleted_at IS NULL'.
                    ')'.
                'where name IS NOT NULL';
            $res2 = array_pluck(DB::select($sql2), 'full_name', 'user_id');
        }
        return $res + $res2;
    }

    static function fullImagePath() {
        $sql = 'SELECT user_id, image FROM customer WHERE image IS NOT NULL UNION SELECT user_id, avatar as image FROM client where avatar IS NOT NULL';
        $fullImagePath = DB::select($sql);
        foreach ($fullImagePath as $row) {
            $row->image = !empty($row->image) ? asset(self::$folderImageUpload.'medium/'.$row->image) : null;
        }
        if (!empty($fullImagePath)) {
            return array_pluck($fullImagePath, 'image', 'user_id');
        }
    }

    /**
     * Get rating of user (customer)
     */
    public function getRatingOfCustomerAttribute() {
        $sql = sprintf('SELECT value, vote_number, (100*vote_number/('.
            'SELECT SUM(vote_number) AS total_vote FROM ('.
            'SELECT value, COUNT(review_id) AS vote_number FROM user_reviews '.
            'WHERE target_type="'. REVIEW_CUSTOMER_REVIEW_TYPE .'" '.
            'AND target_id = %d '.
            'GROUP BY value '.
            ') AS total_vote '.
            ')) AS percentage '.
            'FROM ('.
            'SELECT value, COUNT(review_id) AS vote_number FROM user_reviews '.
            'WHERE target_type="'. REVIEW_CUSTOMER_REVIEW_TYPE .'" '.
            'AND target_id = %d '.
            'GROUP BY value '.
            'ORDER BY value DESC '.
            ') AS tb_summary', $this->id, $this->id);

        return $this->processRatingCustomerReview(DB::select($sql));
        /*dd(response()->json($userReview));
        return Response::toJSON($userReview);*/
    }

    /**
     * Get rating of user(client) for other user(customer)
     */
    public function getUserRatingForAttribute() {
        $sql = sprintf('SELECT value, vote_number, (100*vote_number/('.
            'SELECT SUM(vote_number) AS total_vote FROM ('.
            'SELECT value, COUNT(review_id) AS vote_number FROM user_reviews '.
            'WHERE target_type="'. REVIEW_CUSTOMER_REVIEW_TYPE .'" '.
            'AND user_review_id = %d '.
            'GROUP BY value '.
            ') AS total_vote '.
            ')) AS percentage '.
            'FROM ('.
            'SELECT value, COUNT(review_id) AS vote_number FROM user_reviews '.
            'WHERE target_type="'. REVIEW_CUSTOMER_REVIEW_TYPE .'" '.
            'AND user_review_id = %d '.
            'GROUP BY value '.
            'ORDER BY value DESC '.
            ') AS tb_summary', $this->id, $this->id);

        return $this->processRatingCustomerReview(DB::select($sql));
        /*dd(response()->json($userReview));
        return Response::toJSON($userReview);*/
    }

    public function liked_for() {
        return $this->hasMany('JP_COMMUNITY\Models\Like', 'target_id', 'id');
    }

    public function message_sender() {
        return $this->hasMany('JP_COMMUNITY\Models\Message', 'sender_id', 'id');
    }

    public function message_received() {
        return $this->hasMany('JP_COMMUNITY\Models\Message', 'received_id', 'id');
    }

    /**
     * Note: Don't use this Accessor in loop
     */
    public function getLikedForAttribute() {

        return $this->liked_for()
            ->where('target_type', LIKE_CUSTOMER_LIKE_TYPE)
            ->where('value', 1)
            ->get();
    }

    public function liked_of() {
        return $this->hasMany('JP_COMMUNITY\Models\Like', 'user_like_id', 'id');
    }
    /**
     * Note: Don't use this Accessor in loop
     */
    public function getLikedOfAttribute() {

        return $this->liked_of()
            ->where('target_type', LIKE_CUSTOMER_LIKE_TYPE)
            ->where('value', 1)
            ->get();
    }

    public function reviewed_for() {
        return $this->hasMany('JP_COMMUNITY\Models\UserReview', 'target_id', 'id');
    }

    public function getReviewedForAttribute() {
        return $this->reviewed_for()
            ->where('target_type', REVIEW_CUSTOMER_REVIEW_TYPE)
            ->get();
    }

    public function reviewed_of() {
        return $this->hasMany('JP_COMMUNITY\Models\UserReview', 'user_review_id', 'id');
    }

    public function getReviewedOfAttribute() {
        return $this->reviewed_of()
            ->where('target_type', REVIEW_CUSTOMER_REVIEW_TYPE)
            ->get();
    }

    /**
     * get user_type
     * @return bool
     */
    public function getIsClientAttribute() {
        return $this->user_type === 'is_client' ? true : false;
    }
    public function getIsCustomerAttribute() {
        return $this->user_type === 'is_customer' ? true : false;
    }
    public function getIsStaffAttribute() {
        return $this->user_type === 'is_staff' ? true : false;
    }
    public function getIsAdminAttribute() {
        return $this->user_type === 'is_admin' ? true : false;
    }

    /**
     * Note: Don't use this Accessor in loop
     */
    public function getNumberReviewedForAttribute() {

        $userReview = DB::select('SELECT count(review_id) as total FROM user_reviews where target_id = ? and target_type = ?', [$this->id, REVIEW_CUSTOMER_REVIEW_TYPE]);

        return !empty($userReview[0]) ? $userReview[0]->total : 0;
    }

    /**
     * Note: Don't use this Accessor in loop
     */
    public function getNumberReviewedOfAttribute() {

        $userReview = DB::select('SELECT count(review_id) as total FROM user_reviews where user_review_id = ? and target_type = ?', [$this->id, REVIEW_CUSTOMER_REVIEW_TYPE]);

        return !empty($userReview[0]) ? $userReview[0]->total : 0;
    }

    /**
     * Get all reviewed for an user
     * Note: Don't use this Accessor in loop
     */
    public function getReviewedForUserAttribute() {
        $reviewedOfUser = UserReview::where('target_type', 'customer_review')->where('target_id', $this->id)
                        ->orderBy('created_at', 'DESC')
                        ->limit(5)
                        ->get();
        return $reviewedOfUser;
    }

    /**
     * get tota
     * @return array['user_id' => number reviewed]
     */
    static function getNumberReviewedOfUsers($option = null) {
        if (!empty($option)) {
            $sql = 'SELECT user_review_id AS user_id, count(review_id) AS number_reviewed FROM user_reviews'
                .' where target_type = "'. $option
                .'" group by user_id';
        } else {
            $sql = 'SELECT user_review_id AS user_id, count(review_id) AS number_reviewed FROM user_reviews'
                .' group by user_id';
        }

        return array_pluck(DB::select($sql), 'number_reviewed', 'user_id');
    }

    /**
     * Get number liked of an user
     * @return array ['id_user' => $numberLiked, ...]
     *
     */
    static function getNumberLikedOfUsers($option = null) {

        if (!empty($option)) {
            $sql = 'SELECT user_like_id AS user_id, count(like_id) AS number_liked FROM likes'
                .' where value=1 and target_type = "'. $option
                .'" group by user_id';
        } else {
            $sql = 'SELECT user_like_id AS user_id, count(like_id) AS number_liked FROM likes'
                .' where value = 1'
                .' group by user_id';
        }

        return array_pluck(DB::select($sql), 'number_liked', 'user_id');
    }

    /**
     * Get list user liked for a customer
     * @param null $options
     * @internal param $targetType
     * @return array
     */
    static function getNumberLikedFor($targetType = null) {
        if (empty($targetType)) {
            return [];
        }
        $sql = 'SELECT COUNT(like_id) AS number_liked, target_id FROM likes WHERE value = 1 AND target_type="'. $targetType .'" GROUP BY target_id';

        return array_pluck(DB::select($sql), 'number_liked', 'target_id');
    }

    /**
     * Get number comment for customers
     * @param $targetType
     * @return array
     */
    static function getNumberCommentFor($targetType) {
        if (empty($targetType)) {
            return [];
        }
        $sql = 'SELECT COUNT(comment_id) AS number_comment, target_id FROM comments WHERE deleted_at is null AND target_type="'. $targetType.'" GROUP BY target_id';
        return array_pluck(DB::select($sql), 'number_comment', 'target_id');
    }

    static function userReviewed($targetType, $userId = null) {
        $userId = !empty($userId) ? $userId : Auth::id();
        $res = DB::table('user_reviews')->where('user_review_id', $userId)
            ->where('target_type', $targetType)->selectRaw('target_id, max(value) as value')->groupBy('target_id')->pluck('value', 'target_id');
        return $res;
    }

    public static function toAnArray(&$input) {
        if (is_object($input)) {
            $input = get_object_vars($input);
        }
        foreach ($input as &$item) {
            if (is_object($item) || is_array($item)) {
                if (is_object($item)) {
                    $item = get_object_vars($item);
                }
                self::toAnArray($item);
            }
        }
    }
    protected function processRatingCustomerReview($res) {

        if (count($res) == 5) {
            return $res;
        }

        $tmpObject = [];
        $arrValue = [];
        foreach ($res as $row) {
            $arrValue[] = $row->value;
        }
        for ($i = 1; $i <= 5; $i ++) {
            if (!in_array($i, $arrValue)) {
                $buffObject = (object) [
                    'value' => $i,
                    'vote_number' => 0,
                    'percentage' => 0
                ];
                array_push($tmpObject, $buffObject);
            }
        }
        $res = array_merge($res, $tmpObject);

        $newRes = [];
        foreach ($res as $row) {
            $newRes[$row->value] = $row;
        }
        krsort($newRes);
        return $newRes;
    }
    /**
     * Function check user type
     * @param $type
     * @return bool
     */
    public static function isUserType($user_type) {
        return Auth::user()->user_type == $user_type;
    }

    public function customer()
    {
        return $this->hasOne('JP_COMMUNITY\Models\Customer', 'user_id', 'id');
    }

    public function client()
    {
        return $this->hasOne('JP_COMMUNITY\Models\Client', 'user_id', 'id');
    }

    public function news()
    {
        return $this->hasMany('JP_COMMUNITY\Models\News', 'user_id', 'id');
    }

    public function getNewsAttribute() {
        return $this->news()
            ->where('target_page', CUSTOMER_NEWS)
            ->orWhere('target_page', null)
            ->get();
    }

    public function getNewsPaginateAttribute() {
        return $this->news()->paginate(HOME_ACTIVITY_NEWS_LIMIT);
    }

    public function user_review() {
        return $this->hasMany('JP_COMMUNITY\Models\UserReview', 'user_review_id', 'id');
    }

    public function user_review_for() {
        return $this->hasMany('JP_COMMUNITY\Models\UserReview', 'target_id', 'id');
    }

    public function getUserReviewForAttribute() {
        return $this->user_review_for()->where('target_type', REVIEW_CUSTOMER_REVIEW_TYPE)->get();
    }

    public function getUserReviewForPaginateAttribute() {
        return $this->user_review_for()
            ->where('target_type', REVIEW_CUSTOMER_REVIEW_TYPE)
            ->orderBy('created_at', 'DESC')
            ->paginate(HOME_USER_REVIEW_LIMIT);
    }

    public function user_comment_for() {
        return $this->hasMany('JP_COMMUNITY\Models\Comment', 'target_id', 'id');
    }

    public function getUserCommentForAttribute() {
        return $this->user_comment_for()->where('target_type', COMMENT_CUSTOMER_COMMENT_TYPE)->get();
    }
}
