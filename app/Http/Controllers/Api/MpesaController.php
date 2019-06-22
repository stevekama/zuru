<?php

namespace App\Http\Controllers\Api;

use App\Models\Account;
use App\Models\MpesaTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Webpatser\Uuid\Uuid;

class MpesaController extends Controller
{
    //

    public function process(Request $request)
    {
        if($request->has('key') && request('key')=="Fpe1kFh8zgjoCqAVSEF7l0CKfpHonmgj"){

            $data = $request->json()->all();

            /*
             * verify the data belongs to an account
             */

            MpesaTransaction::create($data);

            /*
             * increase corresponding wallet
             */

            $_data = explode("-",$data['BillRefNumber']);
            $account = Account::where('sequence_one',$_data[0])
                ->where('sequence_four',$_data[1])
                ->first();
            if($account!=null){
                $account->balance+=$data['TransAmount'];
                $account->save();


                $trans_data = [
                    'account_id'=>$account->id,
                    'id'=>Uuid::generate()->string,
                    'amount'=>$data['TransAmount'],
                    'type'=>1
                ];
                Transaction::create($trans_data);
            }

            /*
             *TODO Dispatch event to firebase for this account update
             */




            $response=[
                'ResultCode'=>0,
                'ResultDesc'=>'The service was accepted successfully',
                'ThirdPartyTransID'=>$data['ThirdPartyTransID']
            ];
            return response()->json($response);

        }else{

            $response=[
                'ResultCode'=>0,
                'ResultDesc'=>'Unauthorised access',
                'ThirdPartyTransID'=>uniqid()
            ];
            return response()->json($response);
        }
    }
}
