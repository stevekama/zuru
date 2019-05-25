<?php

namespace App\Http\Controllers\Api\Access;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

class UsersController extends Controller
{
    //
    public function user()
    {
        return response()->json(Auth::user());
    }

    public function updateUser(Request $request)
    {

        $this->validate($request,[
            'email'=>'required',
            'password'=>'required'
        ]);

        $user = Auth::user();
        $user->name = request('name');

        /*
         * upload avatar if present
         */
        if($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            #requery vital data for the upload
            $image = Input::file('avatar');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path('files/users/');
            #create path if it does not exist and move the trimmed file
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($image->getRealPath())->fit(500, 500)->save($path . $filename);
            $user->avatar = $filename;

        }

        $user->save();

        return response()->json([
            'success'=>true
        ]);

    }






}
