<?php

namespace JP_COMMUNITY\Models;


use Illuminate\Database\Eloquent\Model;
use JP_COMMUNITY\Http\Traits\TimezoneAccessor as TimezoneAccessor;

class BaseModel extends Model
{
    use TimezoneAccessor;
    protected $dateFormat = 'Y-m-d h:i';
}