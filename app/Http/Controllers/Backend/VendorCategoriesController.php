<?php

namespace App\Http\Controllers\Backend;

use App\Models\VendorCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Intervention\Image\Facades\Image;

class VendorCategoriesController extends Controller
{

    public function list()
    {
        return View::make('backend.categories.index')->withCategories(VendorCategory::all());
    }

    public function edit(VendorCategory $category)
    {
        Session::flash('_old_input',$category);
        return View::make('backend.categories.form');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'=>'required_without:id',
            'avatar'=>'required_without:id'
        ]);

        if($validator->fails()){
            return back()->withInput()->withErrors($validator);
        }

        if($request->has('id') && request('id')!=null){
            $cat = VendorCategory::find($request->input('id'));
        }else{
            $cat  =new VendorCategory();
        }


        /*
         * upload avatar if possible
         */

        if($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            #requery vital data for the upload
            $image = Input::file('avatar');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $path = public_path('files/categories/');
            #create path if it does not exist and move the trimmed file
            if (!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            Image::make($image->getRealPath())->fit(500, 500)->save($path . $filename);
            $cat->avatar = $filename;

        }

        $cat->name = request('name');
        $cat->save();
        return redirect()->route('backend.vendor_categories.list');
    }

    public function create()
    {
        return View::make('backend.categories.form');
    }
}
