@extends('backend.layouts.admin')

@section('bread')
    Rider modes
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Products</h3>
                    <div class="box-tools">
                        <a href="{{route('backend.rider_modes.create')}}">create new</a>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Max distance</th>
                            <th>Price per km</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($riderModes as $product)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$product->avatar}}</td>
                                <td>{{$product->name}}</td>
                                <td>{{$product->max_available_distance}}</td>
                                <td>{{$product->price_per_km}}</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="{{route('backend.rider_modes.edit',$product->id)}}"><i class="fa fa-edit"></i></a>
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
