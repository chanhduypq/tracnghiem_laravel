<?php

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JP_COMMUNITY\Models\Report;

class ReportController extends Controller
{
    /**
     * This function should add report
     */
    public function report(Request $request) {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'require_login' => true]);
        }
        $this->validate($request, [
            'target_id' => 'required|integer',
            'target_type' => 'string|required|in:'.REPORT_CUSTOMER_REVIEWED_TYPE,
            'content' => 'string',
        ]);

        $res = Report::where('target_id', $request->get('target_id'))
            ->where('target_type', $request->get('target_type'))->first();
        if (!empty($res)) {
            $res->update([
               'value' => $res->value === VALUE_REPORT ? VALUE_CANCEL_REPORT : VALUE_REPORT
            ]);
        } else {
            $res = Report::create([
                'user_report_id' => Auth::id(),
                'target_type' => $request->get('target_type'),
                'target_id' => $request->get('target_id'),
                'value' => VALUE_REPORT
            ]);
        }
        if (!empty($res)) {
            return response()->json([
               'success' => true,
                'data' => [
                    'target_type' => $res->target_type,
                    'target_id' => $res->target_id,
                    'value' => $res->value,
                ]
            ]);
        }
    }
}
