<?php

namespace JP_COMMUNITY\Providers;

use Collective\Html\HtmlServiceProvider as HTML;
use Collective\Html\FormBuilder as Form;
use Illuminate\Support\Facades\Auth;

class MacroServiceProvider extends HTML
{
    public function boot() {

        Form::macro('thumbnail', function($src, $option = [])
        {
            $thumbnail = $src;
            return view('macros.thumbnail', compact('thumbnail'));
        });

        /**
         * Form user review
         * @param array $target_id
         */
        Form::macro('user_review', function($targetUser)
        {
            // Only Client cant review for a customer
            $user = null;
            if (Auth::check() && Auth::user()->user_type == 'is_client') {
                $user = Auth::user()->load('client');
            }

            // Only Client can review for a customer.
            if (Auth::check() && Auth::user()->user_type != 'is_client') {
                return null;
            }
            return view('macros.user_review', compact('targetUser', 'user'));
        });
        /**
         * Form user review
         * @param array $target_id
         */
        Form::macro('user_reviewed', function($user_reviews)
        {

            if (empty($user_reviews)) {
                return null;
            }
            // Only Client cant review for a customer
            return view('macros.user_reviewed', compact('user_reviews'));
        });

        Form::macro('date_picker', function ($name, $default = null, $options =[]){
            return view('macros.date_picker', compact('name', 'default', 'options'));
        });

        /**
         * HTML btn_like
         * @param array $arrayUserLiked
         * @param int $targetId
         * @param string $targetType
         */
        Form::macro('btn_like', function ($arrayUserLiked, $targetId, $targetType) {
            // Make user array liked id
            $userLiked = [];
            foreach ($arrayUserLiked as $row) {
                $userLiked[] = $row->user_like_id;
            }
            return view('macros.btn_like', compact('userLiked', 'targetType', 'targetId'));
        });

        /**
         * Macro user comment
         */
        Form::macro('user_comment', function ($comments, $targetId, $targetType) {
            return view('macros.user_comment', compact('comments', 'targetId', 'targetType'));
        });
    }

}