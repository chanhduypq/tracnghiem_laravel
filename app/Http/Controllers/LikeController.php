<?php

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JP_COMMUNITY\Models\Like;

class LikeController extends Controller
{
    public function like(Request $request) {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'require_login' => true]);
        }
        $this->validate($request, [
           'target_type' => 'required|string',
            'target_id' => 'required|integer',
            'value' => 'required|integer'
        ]);
        $userLike = Like::where('target_type', $request->get('target_type'))
            ->where('target_id', $request->get('target_id'))
            ->where('user_like_id', Auth::id())->first();
        if (empty($userLike)) {
            Like::create([
                'user_like_id' => Auth::id(),
                'target_type' => !empty($request->get('target_type')) ? $request->get('target_type') : null,
                'target_id' => !empty($request->get('target_id')) ? $request->get('target_id') : null,
                'value' => $request->has('value') ? $request->get('value') : null,
            ]);
        } else {
            $userLike->update([
               'value' => $request->has('value') ? $request->get('value') : $userLike->value
            ]);
        }
        return response()->json(['success' => true]);
    }
}
