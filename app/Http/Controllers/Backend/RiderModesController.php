<?php

namespace App\Http\Controllers\Backend;

use App\Models\RiderModes;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

class RiderModesController extends Controller
{
    //

    public function list()
    {
        return View::make('backend.rider_modes.index')->withRiderModes(RiderModes::all());
    }

    public function create()
    {
        return View::make('backend.rider_modes.form');
    }

    public function edit(RiderModes $riderModes)
    {


        Session::flash('_old_input',$riderModes);
        return View::make('backend.rider_modes.form');

    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'price_per_km'=>'required',
            'max_available_distance'=>'required'
        ]);

        if($validator->fails()){
            return back()->withInput()->withErrors($validator);
        }

        if($request->has('id') && $request->input('id')!=null){
            $mode = RiderModes::find(request('id'));
        }else{
            $mode = new RiderModes();
        }

        if($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            #requery vital data for the upload
            $image = Input::file('avatar');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path('files/rider_modes/');
            #create path if it does not exist and move the trimmed file
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($image->getRealPath())->fit(500, 500)->save($path . $filename);
            $mode->avatar = $filename;

        }

        $mode->name = request('name');
        $mode->price_per_km = request('price_per_km');
        $mode->max_available_distance = request('max_available_distance');
        $mode->save();

        return back();

    }
}
