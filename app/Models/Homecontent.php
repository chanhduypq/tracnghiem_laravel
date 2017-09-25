<?php

namespace JP_COMMUNITY\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use JP_COMMUNITY\Http\Scopes\ActiveScope;

class Homecontent extends BaseModel
{
    use SoftDeletes;
    protected $table = 'home_content';
   

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
    }

   

    
}
