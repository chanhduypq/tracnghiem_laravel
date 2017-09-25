<?php
namespace JP_COMMUNITY\Http\Controllers\Facades;

use JP_COMMUNITY\Http\Controllers\Contracts\Youtube as YoutubeContract;
use Illuminate\Support\Facades\Facade;

class Youtube implements Facade
{
    protected static function getFacadeAccessor() {
        return YoutubeContract::class;
    }
}