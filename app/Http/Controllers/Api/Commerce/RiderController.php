<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Rider;
use App\Models\RiderModes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;
use Webpatser\Uuid\Uuid;

class RiderController extends Controller
{
    //
    public function getSelfRider()
    {
        $user = Auth::user();
        $user->login_mode = 2;
        $user->save();
        return response()->json($user->rider);

    }

    public function getUserRider()
    {
        $user = Auth::user();
        $user->login_mode = 2;
        $user->save();
        return response()->json(['user'=>$user,'rider'=>Rider::where('user_id',$user->id)->withCount('rides')->first()]);
    }


    public function storeRider(Request $request)
    {
        $this->validate($request,
            ['latitude'=>'required',
                'longitude'=>'required']);
        $rider = $request->except('rider_mode');
        $rider['id'] = Uuid::generate();
        $rider['user_id'] = Auth::id();
        $rider['mode_id'] = request('rider_mode');

        if($request->hasFile('license') && $request->file('license')->isValid()) {
            #requery vital data for the upload
            $image = Input::file('license');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path('files/riders/license/');
            #create path if it does not exist and move the trimmed file
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($image->getRealPath())->fit(500, 500)->save($path . $filename);
            $rider['license'] = $filename;

        }

        Rider::createOrUpdateExcept(['user_id'=>Auth::id()],$rider,['id']);

        return response()->json([
            'success'=>true
        ]);
    }

    public function riderModes()
    {
        return response()->json(RiderModes::all());
    }
}
