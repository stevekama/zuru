<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    //

    public function index()
    {
        return View::make('backend.dashboard.index');
    }
}
