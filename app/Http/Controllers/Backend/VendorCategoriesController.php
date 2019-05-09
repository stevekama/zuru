<?php

namespace App\Http\Controllers\Backend;

use App\Models\VendorCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

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
            'name'=>'required|unique:vendor_categories'
        ]);

        if($validator->fails()){
            return back()->withInput()->withErrors($validator);
        }

        if($request->has('id') && request('id')!=null){
            $cat = VendorCategory::find($request->input('id'));
        }else{
            $cat  =new VendorCategory();
        }

        $cat->name = request('name');
        $cat->save();
        return back();
    }

    public function create()
    {
        return View::make('backend.categories.form');
    }
}
