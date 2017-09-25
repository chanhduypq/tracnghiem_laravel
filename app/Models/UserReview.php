<?php

namespace JP_COMMUNITY\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class UserReview extends BaseModel
{
    use SoftDeletes;
    protected $table = 'user_reviews';
    protected $fillable = [
        'user_review_id',  'target_type', 'target_id', 'value','title', 'content'
    ];
    protected $primaryKey = 'review_id';
    public $timestamps = true;
    protected $dates = ['deleted_at', 'update_at'];

    public function user() {
        return $this->belongsTo('JP_COMMUNITY\Models\User', 'user_review_id', 'id');
    }

    public function client() {
        return $this->belongsTo('JP_COMMUNITY\Models\Client', 'user_review_id', 'user_id');
    }

    public function comments() {
        return $this->hasMany('JP_COMMUNITY\Models\Comment', 'target_id', 'review_id');
    }

    public function getCommentsAttribute() {
        return $this->comments()->where('target_type', COMMENT_CUSTOMER_REVIEW_TYPE)->get();
    }
}
