<?php

namespace JP_COMMUNITY\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JP_COMMUNITY\Models\MessageExtends;
use League\Flysystem\Exception;
use Illuminate\Support\Facades\DB;
use JP_COMMUNITY\Models\Message;

class MessageController extends BaseController
{

    /**
     * List message of an user
     * @param int $id User ID
     */
    public function index($id) {
        return view('messages.index');
    }

    /**
     * Detail message
     * @param $id
     * @param $message_id
     */
    public function show($id, $message_id) {
        $showMessage = null;
        if (!empty($message_id)) {
            $showMessage = Message::find($message_id);

            $messageExtends = MessageExtends::where('user_id', $showMessage->received_id)
                ->where('message_id', $showMessage->message_id)
                ->where('person_type', MESSAGE_RECEIVED_PERSON)
                ->update(['status' => 1]);
        }

        return view('messages.index', compact('showMessage'));
    }

    /**
     * Message detail via ajax
     */
    public function message_detail(Request $request) {
        $this->validate($request, [
           'data_id' => 'required|integer'
        ]);

        $res = Message::where('message_id', $request->get('data_id'))->with('message_received_extends')->first();
        if (empty($res)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'message' => trans('message.not_found'),
                    'code' => 402
                ]
            ]);
        }

        $resMessageExtends = MessageExtends::where('message_id', $res->message_id)
            ->where('user_id', Auth::id())
            ->where('person_type', MESSAGE_RECEIVED_PERSON)
            ->update([
                'status' => 1
            ]);
        return response()->json([
            'success' => true,
            'data' => [
                'message_id' => $res->message_id,
                'sender_id' => $res->sender_id,
                'received_id' => $res->received_id,
                'subject' => $res->subject,
                'content' => nl2br($res->content),
                'created_at' => $res->created_at,
                'message_extends' => $res->message_extends
            ]
        ]);
    }

    public function send(Request $request) {

        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 401,
                    'message' => trans('message.login_required')
                ]
            ]);
        }

        $this->validate($request, [
            'user_id' => 'required|integer',
            'subject' => 'required|string',
            'content' => 'required|string'
        ]);

        DB::beginTransaction();

        try{
            $res = Message::create([
                'sender_id' => Auth::id(),
                'received_id' => $request->get('user_id'),
                'subject' => $request->get('subject'),
                'content' => $request->get('content')
            ]);

            $resMessageExtends = MessageExtends::insert([
                [
                    'message_id' => (int) $res->message_id,
                    'user_id' => (int) $res->sender_id,
                    'status' => MESSAGE_UNREAD,
                    'person_type' => MESSAGE_SENDER_PERSON,
                ],
                [
                    'message_id' => (int) $res->message_id,
                    'user_id' => (int) $res->received_id,
                    'status' => MESSAGE_UNREAD,
                    'person_type' => MESSAGE_RECEIVED_PERSON,
                ],
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false, 'message' => trans('message.error_occur')
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'data' => $res
        ]);
    }
}
