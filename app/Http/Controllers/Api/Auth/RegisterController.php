<?php

namespace App\Http\Controllers\Api\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;

class RegisterController extends Controller
{
    //
    private  $client;

    public function __construct()
    {
        $this->client=Client::find(2);
    }
    public function register(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
            'name'=>'required|string|max:255',
            'login_mode'=>'required'
        ]);

        //register the user
        $user=User::create([

            'email'=>request('email'),
            'password'=>bcrypt(request('password')),
            'name'=>request('name'),
            'level'=>0,
            'login_mode'=>request('login_mode'),
        ]);

        $params=[
            'grant_type'=>'password',
            'client_id'=>$this->client->id,
            'client_secret'=>$this->client->secret,
            'username'=>request('email'),
            'password'=>request('password'),
            'scope'=>'*'
        ];


        $request->request->add($params);

        $proxy=Request::create('oauth/token','POST');

        return Route::dispatch($proxy);
    }
}
