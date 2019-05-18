<?php

namespace App\Http\Controllers\Api\Commerce;

use App\Models\Account;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Webpatser\Uuid\Uuid;

class AccountController extends Controller
{
    //
    public function getUserAccount()
    {
        $user = Auth::user();

        if($user->account==null){
            $this->createUserAccount($user);
        }

        $user->load('account.transactions');

        return response()->json($user);
    }

    function generateRandom($digits = 4){
        $i = 0; //counter
        $pin = ""; //our default pin is blank.
        while($i < $digits){
            //generate a random number between 0 and 9.
            $pin .= mt_rand(0, 9);
            $i++;
        }
        return $pin;
    }

    public function createUserAccount($user)
    {
        $acc = new Account();
        $acc->id = Uuid::generate();
        $acc->user_id = $user->id;
        $acc->sequence_one = $this->generateRandom();
        $acc->sequence_two = $this->generateRandom();
        $acc->sequence_three = $this->generateRandom();
        $acc->sequence_four = $this->generateRandom();
        $acc->expiry = Carbon::now()->addYears(5);
        $acc->save();
    }
}
