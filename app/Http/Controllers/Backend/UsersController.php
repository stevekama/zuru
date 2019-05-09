<?php

namespace App\Http\Controllers\Backend;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;

class UsersController extends Controller
{
    //
    public function list($login_mode)
    {

        $users = User::where([['login_mode',$login_mode],['user_type',1]])->get();
        return View::make('backend.users.list')->withUsers($users);
    }

    public function admins()
    {
        $users = User::where([['user_type',0]])->get();
        return View::make('backend.users.admins')->withUsers($users);
    }

    public function vendorInformation(User $user)
    {
        Session::flash('_old_input',$user->vendor);
        return back();
    }
}
