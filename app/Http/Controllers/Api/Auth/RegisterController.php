<?php

namespace App\Http\Controllers\Api\Auth;

use App\Mail\ResetPasswordCode;
use App\Models\ResetCode;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
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

        $v = Validator::make($request->all(),[
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed',
            'name'=>'required|string|max:255',
            'login_mode'=>'required'
        ]);
        if($v->fails()){

            return response()->json([
                'success'=>false,
                'message'=>implode(",",$v->messages()->all())
            ],422);

        }


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


    public function resetCode(Request $request)
    {

        $v = Validator::make($request->all(),[
            'email'=>'required|exists:users'
        ]);
        if($v->fails()){

            return response()->json([
                'success'=>false,
                'message'=>implode(",",$v->messages()->all())
            ],422);

        }



        $code = $this->generateUniqueCode();
        $user = User::where('email',request('email'))->first();

        $resetCode = ResetCode::where('user_id',$user->id)->where('active',false)->first();
        if($resetCode==null){
            $resetCode = new ResetCode();
            $resetCode->user_id = $user->id;
            $resetCode->code = $code;
            $resetCode->save();
        }

        Mail::to($user->email)
            ->queue(new ResetPasswordCode($resetCode));

        return response()->json([
            'success'=>true,
            'message'=>'Reset code sent to email successfully'
        ]);


    }

    public function resetPassword(Request $request)
    {
        $validator =Validator::make($request->all(),[
            'code'=>'required|exists:reset_codes',
            'password'=>'required'
        ]);



        if($validator->fails()){
            return response()->json([
                'success'=>false,
                'message'=>'The provided details are invalid'
            ]);
        }

        $code = ResetCode::where('code',request('code'))->orderBy('id','desc')->first();
        if($code==null){
            return response()->json([
                'success'=>false,
                'message'=>'The provided code is already used'
            ]);
        }

        $user = $code->user;
        $user->password=bcrypt(request('password'));
        $user->save();

        return response()->json([
            'success'=>true
        ]);


    }

    function generateUniqueCode(){
        $code = $this->generatePIN();
        $validator = Validator::make(["code"=>$code],["code"=>"required|unique:reset_codes"]);
        if($validator->fails())
            return $this->generateUniqueCode();
        return $code;
    }

    function generatePIN($digits = 4){
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while($i < $digits){
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }
}
