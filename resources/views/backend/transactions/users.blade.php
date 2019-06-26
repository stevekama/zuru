@extends('backend.layouts.admin')

@section('bread')
    Transactions
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Transactions</h3>
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
                            <th>Account no</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>KSH. {{number_format($transaction->amount,2)}}</td>
                                <td>
                                    @if($transaction->account!=null)
                                        @php($acc = $transaction->account)
                                            {{$acc->sequence_one . "-" . $acc->sequence_two . "-" . $acc->sequence_three . "-" . $acc->sequence_four}}
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
