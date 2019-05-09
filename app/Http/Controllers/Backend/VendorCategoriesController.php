<?php

namespace App\Http\Controllers\Backend;

use App\Models\VendorCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class VendorCategoriesController extends Controller
{

    public function list()
    {
        return View::make('backend.categories.index')->withCategories(VendorCategory::all());
    }
}
