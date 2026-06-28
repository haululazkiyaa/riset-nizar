<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;

class MeController extends Controller
{
    public function show(): UserResource
    {
        return new UserResource(request()->user()->load('roles'));
    }
}
