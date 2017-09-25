<?php

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use JP_COMMUNITY\Mail\GetCatalog;

class LogsController extends BaseController
{
    /**
     * This function execute case user click button get catalog.
     * @param Request $request
     */
    public function getCatalog(Request $request) {
        if (!Auth::check()) {
            return response()->json([
                'requireLogin' => true,
                'message' => trans('message.require_login'),
                'error_code' => 404,
            ]);
        }
        $this->validate($request, [
            'email_customer' => 'email',
            'target_id' => 'required|integer',
            'target_type' => 'required|string',
        ]);
        if (!empty($request->get('email_customer'))) {
            // Send email to customer
//            $mail = Mail::to($request->get('email_customer'))->send(new GetCatalog());
        }
        $content = (object) [
            'user_id' => Auth::id(),
            'name_customer' => $request->get('name_customer'),
            'email_customer' => $request->get('email_customer')
        ];

        // Save to logs table
        DB::table('logs')->insert([
            'target_id' => $request->get('target_id'),
            'target_type' => $request->get('target_type'),
            'content' => json_encode($content)
        ]);

        return response()->json([
            'success' => true,
        ]);

    }
}
