<?php

namespace App\Http\Controllers\Api\Access;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class UsersController extends Controller
{
    //
    public function user()
    {
        return response()->json(Auth::user());
    }


}
