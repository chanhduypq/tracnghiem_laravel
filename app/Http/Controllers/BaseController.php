<?php

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use JP_COMMUNITY\Models\Comment;
use JP_COMMUNITY\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;


class BaseController extends Controller
{
    public $param=array();
    
    protected $checkbox = ['checkbox'];
    protected $arrayToJsonField = [];


    public function __construct()
    {
        $this->middleware(
        /**
         * @param $request
         * @param $next
         * @return mixed
         */
        function ($request, $next) {

//            $userType = Auth::check() ? Auth::user()->user_type : null;
//
//            $authID = Auth::id();
//
//            /*$sql = 'SELECT DISTINCT m.message_id, m.sender_id, m.received_id, m.subject, m.content, me.status, m.created_at, m.sender_deleted_at, m.received_deleted_at'.
//                    ' FROM message as m'.
//                    ' JOIN message_extends AS me'.
//                    ' ON m.message_id = me.message_id AND m.received_id = me.user_id'.
//                    ' WHERE me.user_id = ?'.
//                    ' ORDER BY me.status ASC'.
//                    ' limit ?, ?';
//            $messages = DB::select($sql, [$authID, 0, 10]);*/
//            $received = DB::table('message')->join('message_extends', function ($join) {
//                            $join->on('message.message_id', 'message_extends.message_id')
//                            ->on('message.received_id', '=', 'message_extends.user_id');
//                        })->where('message_extends.user_id', $authID)
//                        ->where('message_extends.person_type', MESSAGE_RECEIVED_PERSON)
//                        ->orderBy('message_extends.status', 'ASC')
//                        ->select('message.*', 'message_extends.status', 'message_extends.person_type')
//                        ->paginate(5);
//            $unread = DB::table('message')->join('message_extends', function ($join) {
//                        $join->on('message.message_id', 'message_extends.message_id')
//                            ->on('message.received_id', '=', 'message_extends.user_id');
//                        })->where('message_extends.user_id', $authID)
//                        ->where('message_extends.person_type', MESSAGE_RECEIVED_PERSON)
//                        ->where('message_extends.status', 0)
//                        ->orderBy('message_extends.created_at', 'DESC')
//                        ->select('message.*', 'message_extends.status', 'message_extends.person_type')
//                        ->paginate(5);
//
//            $messBuff = [
//                    'received' => $received,
//                    'sender' => [],
//                    'unread' => $unread
//                ];
//            $authInfo = [];
//            /*foreach ($messages as $row) {
//                if ($row->sender_id == $authID && $row->person_type == MESSAGE_SENDER_PERSON) {
//                    array_push($messBuff['sender'], $row);
//                }
//
//                if ($row->received_id == $authID && $row->person_type == MESSAGE_RECEIVED_PERSON) {
//                    array_push($messBuff['received'], $row);
//                }
//
//                if ($row->received_id == $authID && $row->status == 0 && $row->person_type == MESSAGE_RECEIVED_PERSON) {
//                    array_push($messBuff['unread'], $row);
//                }
//            }*/
//
//            $authInfo['message'] = $messBuff;
//            $userFullNames = User::usersFullName();
//
//            $fullPathImage = User::fullImagePath();
//
//            $curUserReviewed = User::userReviewed(REVIEW_CUSTOMER_REVIEW_TYPE);
//
//            $resLocations = DB::table('locations')->get();
//
//            $cities = [];
//            foreach ($resLocations as $location) {
//                if (!empty($location->country_code)) {
//                    $cities[$location->country_code][$location->location_id] = $location->city_name;
//                }
//            }
//
//            view()->share('_cities', $cities);
//
//            view()->share('_curUserReviewed', $curUserReviewed);
//            view()->share('authInfo', $authInfo);
//            view()->share('userFullNames', $userFullNames);
//            view()->share('fullPathImage', $fullPathImage);
//            view()->share('_userType', $userType);

            $routeArray = app('request')->route()->getAction();
            $controllerAction = class_basename($routeArray['controller']);
            list($controller, $action) = explode('@', $controllerAction);
            $controller = strtolower(str_replace('Controller', '', $controller));
            if ($controller != 'index' && $controller != 'question') {
                if (!Session::has('user')) {
                    return Redirect::to('/')->send();
                } else {
                    return $next($request);
                }
            } else {
                return $next($request);
            }

//            return $next($request);
        });
    }
    
    /**
     * function common
     * @author Trần Công Tuệ <chanhduypq@gmail.com>
     * @return string
     */
    public function getControllerName()
    {
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        $controller = strtolower(str_replace('Controller', '', $controller));
        return $controller;
    }

    


    /**
     * Handle fix case checkbox not send request when unchecked.
     * Used this function before validate
     * @param Request $request
     * @return Request
     */
    public function prepareFormInput(Request $request) {

        foreach ($this->checkbox as $field) {
            $request->merge([$field => array_key_exists($field, $request->all()) ? 1 : 0]);
        }
        foreach ($this->arrayToJsonField as $field) {
            $request->merge([$field => json_encode($request->get($field))]);
        }
        return $request;
    }

    /**
     * Function build response comment
     * @param array $comments
     * return array $comments
     */
    protected function buildResponseComments($comments, $targetType) {

        if (empty($comments)) {
            return null;
        }

        $commentL1Ids = collect($comments)->pluck('comment_id')->all();

        $commentsL2 = Comment::where('target_type', $targetType)->whereIn('grand_parent_id', $commentL1Ids)->get();
        $commentsL2 = $commentsL2->toArray();
        $resComment = [];
        foreach ($comments as $comment1) {
            $resComment[$comment1['comment_id']] = $comment1;
            foreach ($commentsL2 as $comment2) {
                if ($comment1['comment_id'] === $comment2['grand_parent_id']) {
                    $resComment[$comment1['comment_id']]['childs'][] = $comment2;
                }
            }
        }

        return $resComment;
    }
    
    /**
     * khởi tạo lại session ban đầu
     * có nghĩa là 
     *     ban đầu khi login, lưu thông tin session nào thi bây giờ chỉ lấy lại những thông tin đó, 
     *     những thông tin session mới thêm vào sau này thi hủy đi
     */
    public function resetSession() {
        $identity = Session::get('user');

        foreach ($identity as $key => $value) {
            if (!in_array($key, array(
                        'id',
                        'danh_xung',
                        'full_name',
                        'email',
                        'phone',
                        'password',
                        'is_admin',
                        'user'
                            )
                    )
            ) {
                unset($identity["$key"]);
            }
        }

        Session::set('user',$identity);
    }
    
    public function getUserId() {
        $identity = Session::get('user');
        if (!$identity) {
            return -1;
        } else {
            return $identity['id'];
        }
    }
}
