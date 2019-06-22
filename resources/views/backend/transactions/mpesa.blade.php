@extends('backend.layouts.admin')

@section('bread')
    Transactions
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Mpesa Transactions</h3>
                    <div class="box-tools">
                        <a href="{{route('backend.rider_modes.create')}}">create new</a>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Trans ID</th>
                                <th>Amount</th>
                                <th>Bill ref no</th>
                                <th>MSISDN</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$transaction->FirstName . " " . $transaction->LastName}}</td>
                                <td>{{$transaction->TransID}}</td>
                                <td>{{$transaction->TransAmount}}</td>
                                <td>{{$transaction->BillRefNumber}}</td>
                                <td>{{$transaction->MSISDN}}</td>
                                <td>{{$transaction->created_at}}</td>

                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
