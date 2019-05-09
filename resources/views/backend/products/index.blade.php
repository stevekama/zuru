@extends('backend.layouts.admin')

@section('bread')
    Products
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Products</h3>
                    <div class="box-tools">
                        <a href="{{route('backend.products.create')}}">create new</a>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Avatar</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $product)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$product->avatar}}</td>
                                <td>{{$product->name}}</td>
                                <td>{{$product->description}}</td>
                                <td>{{$product->price}}</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="{{route('backend.products.edit',$product->id)}}"><i class="fa fa-edit"></i></a>
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
