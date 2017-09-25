<?php

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JP_COMMUNITY\Models\User;
use JP_COMMUNITY\Models\UserReview;

class UserReviewController extends BaseController
{
    protected $checkbox = ['review_confirm'];

    /**
     * Show the form for creating a new Review.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $user_id)
    {
        if (!User::isUserType('is_client')) {
            return abort('404', trans('validate.permission_required'));
        }

        $dataPost = null;
        if ($request->isMethod("POST")) {
            $dataPost = $request->all();
        }

        $user = User::find($user_id);
        if (!empty($user) && $user->user_type == 'is_customer') {
            $user = $user->load('customer');
            return view('users.review', compact(['user', 'dataPost']));
        }
    }

    public function store(Request $request)
    {
        if (!User::isUserType('is_client') && $request->get('target_type') == 'customer_review') {
            return abort('404', trans('validate.permission_required'));
        }
        $this->prepareFormInput($request);

        $this->validate($request, [
            'value' => 'required|integer|min:1|max:5',
            'target_id' => 'string',
            'target_type' => 'string',
            'title' => 'required|min:10|max:500',
            'content' => 'required|string|min:100',
            'review_confirm' => 'required|boolean'
        ]);

        $resReview = UserReview::create([
            'user_review_id' => Auth::id(),
            'target_id' => !empty($request->get('target_id')) ? $request->get('target_id') : null,
            'target_type' => !empty($request->get('target_type')) ? $request->get('target_type') : null,
            'value' => !empty($request->get('value')) ? $request->get('value') : null,
            'title' => !empty($request->get('title')) ? $request->get('title') : null,
            'content' => !empty($request->get('content')) ? $request->get('content') : null,
        ]);

        return redirect('/');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
