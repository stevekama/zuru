<?php

namespace App\Http\Controllers\Backend;

use App\Models\MpesaTransaction;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TransactionsController extends Controller
{
    //

    public function users()
    {
        return view('backend.transactions.users')->withTransactions(Transaction::all());
    }

    public function withdrawals()
    {
        return view('backend.transactions.withdrawals')->withTransactions(Transaction::where('type',2)->get());

    }

    public function mpesa()
    {
        return view('backend.transactions.mpesa')->withTransactions(MpesaTransaction::get());
    }


}
