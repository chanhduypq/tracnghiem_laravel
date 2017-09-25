<?php

namespace JP_COMMUNITY\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use JP_COMMUNITY\Models\Comment;
use Illuminate\Support\Facades\Auth;
use JP_COMMUNITY\Models\User;

class CommentController extends BaseController
{
    public function addComment(Request $request) {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'require_login' => true]);
        }
        $this->validate($request, [
            'target_type' => 'required|string',
            'target_id' => 'required|integer',
            'parent_id' => 'integer',
            'grand_parent_id' => 'integer',
            'content' => 'required|string',
        ]);

        $res = Comment::create([
            'user_comment_id' => Auth::id(),
            'target_type' => !empty($request->get('target_type')) ? $request->get('target_type') : null,
            'target_id' => !empty($request->get('target_id')) ? $request->get('target_id') : null,
            'content' => $request->has('content') ? $request->get('content') : null,
            'parent_id' => $request->has('parent_id') ? $request->get('parent_id') : null,
            'grand_parent_id' => $request->has('grand_parent_id') ? $request->get('grand_parent_id') : null
        ]);

        $userFullNames = User::usersFullName();
        $fullPathImage = User::fullImagePath();
//        $res->load('user');

        $resReturn = [
            'comment_id' => $res->comment_id,
            'user_comment_id' => $res->user_comment_id,
            'content' => $res->content,
            'created_at' => _ago($res->created_at),
            'target_type' => $res->target_type,
            'target_id' => $res->target_id,
            'parent_id' => $res->parent_id,
            'grand_parent_id' => $res->grand_parent_id,
            'is_owner' => true,
            'user_commented_image' => !empty($fullPathImage[$res->user_comment_id]) ? $fullPathImage[$res->user_comment_id] : null,
            'user_commented_name' => !empty($userFullNames[$res->user_comment_id]) ? $userFullNames[$res->user_comment_id] : null,
        ];

        return response()->json(['success' => true, 'data' => $resReturn]);
    }

    /**
     * Get comment
     */
    public function getComments(Request $request) {

        $this->validate($request, [
            'page' => 'integer',
            'target_id' => 'required|integer',
            'target_type' => 'required|string',
            'per_page' => 'integer',
        ]);

        $res = Comment::where('target_id', $request->get('target_id'))
            ->where('target_type', $request->get('target_type'))
            ->whereNull('grand_parent_id')
            ->orderBy('created_at', 'DESC');
        if (!empty($request->get('page')) && !empty($request->get('per_page'))) {
            $res = $res->skip($request->get('page')*$request->get('per_page'))
                ->take($request->get('per_page'));
        }
        $res = $res->get();

        if (empty($res->toArray())) {
            return response()->json([
               'success' => false,
                'message' => trans('message.no_result'),
            ]);
        }

        $res = $this->buildResponseComments($res->toArray(), $request->get('target_type'));

        $dataReturn = [];

        $userFullNames = User::usersFullName();
        $fullPathImage = User::fullImagePath();

        foreach ($res as $comment) {
            $dataReturn[] = [
                'comment_id' => $comment['comment_id'],
                'user_comment_id' => $comment['user_comment_id'],
                'target_type' => $comment['target_type'],
                'target_id' => $comment['target_id'],
                'parent_id' => $comment['parent_id'],
                'content' => $comment['content'],
                'user_commented_image' => !empty($fullPathImage[$comment['user_comment_id']]) ? $fullPathImage[$comment['user_comment_id']] : null,
                'user_commented_name' => !empty($userFullNames[$comment['user_comment_id']]) ? $userFullNames[$comment['user_comment_id']] : null,
                'created_at' => _ago($comment['created_at']),
                'is_owner' => User::isOwner($comment['user_comment_id']),
                'childs' => !empty($comment['childs']) ? $comment['childs'] : [],
                'show_more_comment_text' =>!empty($comment['childs']) ? trans('global.show_more_comment', ['n' => count($comment['childs'])]) : null,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $dataReturn
        ]);

    }

    /**
     * Get more comment
     * @param Request $request
     */
    public function moreComment(Request $request) {
        $this->validate($request, [
            'page' => 'integer',
            'target_id' => 'required|integer',
            'target_type' => 'required|string',
            'per_page' => 'integer',
        ]);

        $res = Comment::where('target_id', $request->get('target_id'))
            ->where('target_type', $request->get('target_type'))
            ->whereNull('grand_parent_id')
            ->orderBy('created_at', 'DESC')
            ->skip($request->get('page')*$request->get('per_page'))
            ->take($request->get('per_page'))->get();

        if (empty($res->toArray())) {
            return response()->json([
                'success' => false,
                'message' => trans('message.no_result'),
            ]);
        }

        $res = $this->buildResponseComments($res->toArray(), $request->get('target_type'));

        $dataReturn = [];

        $userFullNames = User::usersFullName();
        $fullPathImage = User::fullImagePath();

        foreach ($res as $comment) {
           $dataReturn[] = [
               'comment_id' => $comment['comment_id'],
               'user_comment_id' => $comment['user_comment_id'],
               'target_type' => $comment['target_type'],
               'target_id' => $comment['target_id'],
               'parent_id' => $comment['parent_id'],
               'content' => $comment['content'],
               'user_commented_image' => !empty($fullPathImage[$comment['user_comment_id']]) ? $fullPathImage[$comment['user_comment_id']] : null,
               'user_commented_name' => !empty($userFullNames[$comment['user_comment_id']]) ? $userFullNames[$comment['user_comment_id']] : null,
               'created_at' => _ago($comment['created_at']),
               'is_owner' => User::isOwner($comment['user_comment_id']),
               'childs' => !empty($comment['childs']) ? $comment['childs'] : [],
               'show_more_comment_text' =>!empty($comment['childs']) ? trans('global.show_more_comment', ['n' => count($comment['childs'])]) : null,
            ];
        }

        return response()->json([
           'success' => true,
            'data' => $dataReturn
        ]);
    }

    public function updateComment(Request $request) {
        $this->validate($request, [
            'comment_id' => 'required|integer',
            'content' => 'required|string'
        ]);
        $resComment = Comment::find($request->get('comment_id'));
        if ($resComment && !User::isOwner($resComment->user_comment_id)) {
            return response()->json(['success' => false, 'message' => trans('validate.permission_denied')]);
        }
        $resComment->update(['content' => $request->get('content')]);
        return response()->json(['success' => true, 'data'=> $resComment]);
    }

    /**
     * Delete a comment
     * @param Request $request
     */
    public function deleteComment(Request $request) {
        $this->validate($request, [
            'comment_id' => 'required|integer'
        ]);

        $resBuff = $resComment = Comment::find($request->get('comment_id'));
        if ($resComment && !User::isOwner($resComment->user_comment_id)) {
            return response()->json(['success' => false, 'message' => trans('validate.permission_denied')]);
        }
        $resComment->delete();
        return response()->json([
           'success' => true,
            'is_grand_parent' => empty($resBuff->grand_parent_id) ? true : false,
        ]);
    }
}
