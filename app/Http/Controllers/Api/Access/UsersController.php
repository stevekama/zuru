<?php

namespace App\Http\Controllers\Api\Access;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    //
    public function user()
    {
        return response()->json(Auth::user());
    }
}
