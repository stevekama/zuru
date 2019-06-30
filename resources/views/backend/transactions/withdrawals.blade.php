@extends('backend.layouts.admin')

@section('bread')
    Transactions
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Withdrawals Transactions</h3>
                    <div class="box-tools">
                        <a href="{{route('backend.rider_modes.create')}}">create new</a>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{number_format($transaction->amount,2)}}</td>
                                <td>{{($transaction->user==null)?"":$transaction->user->name}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
