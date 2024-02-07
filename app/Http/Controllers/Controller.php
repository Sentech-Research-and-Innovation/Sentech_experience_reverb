<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Traits\StoreNotificationTrait;
use App\Traits\ActivityLogTrait;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests, StoreNotificationTrait, ActivityLogTrait;
}
