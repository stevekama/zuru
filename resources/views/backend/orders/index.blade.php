@extends('backend.layouts.admin')

@section('bread')
    Orders
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Orders</h3>
                    <div class="box-tools">
                        <a href="{{route('backend.products.create')}}">create new</a>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Amount</th>
                            <th>Order No</th>
                            <th>Status</th>
                            <th>Location</th>
                            <th>Phone</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>KSH. {{$order->items()->sum('selling_price')}}</td>
                                <td>{{$order->order_no}}</td>
                                <td>
                                    @if($order->status==0)
                                        <div class="label label-primary">in progress</div>
                                    @else($order->status==1)
                                        <div class="label label-primary">complete</div>
                                    @endif
                                </td>
                                <td>{{$order->location}}</td>
                                <td>{{$order->phone}}</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="{{route('backend.orders.view',$order->id)}}"><i class="fa fa-eye"></i></a>
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
