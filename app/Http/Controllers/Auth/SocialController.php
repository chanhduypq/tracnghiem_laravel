<?php

namespace JP_COMMUNITY\Http\Controllers\Auth;


use Illuminate\Support\Facades\Auth;
use JP_COMMUNITY\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use JP_COMMUNITY\Models\User;


class SocialController extends BaseController
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required'
        ]);
        $user = User::where('email', $request->get('email'))->first();
        if (!empty($user)) {
            Auth::loginUsingId($user->id);
            return response()->json([
                'success' => true,
                'data' => $request->get('email')
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error_code' => 404,
                'message' => trans('message.not_found'),
            ]);
        }

    }
}