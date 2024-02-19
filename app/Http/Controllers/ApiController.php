<?php

namespace App\Http\Controllers;

use App\Models\Utils;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Termwind\Components\Raw;

class ApiController extends BaseController
{
    public function register(Request $request){
        Utils::success([],'yemi');
    }
}
