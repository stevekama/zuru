@extends('backend.layouts.admin')

@section('bread')
    Products
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Access roles</h3>
                    <div class="box-tools">
                        <a href="{{route('backend.roles.create')}}">create new</a>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Display name</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$role->name}}</td>
                                <td>{{$role->display_name}}</td>
                                <td>{{$role->description}}</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="{{route('backend.roles.edit',$role->id)}}"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-success btn-xs" title="permissions" href="{{route('backend.roles.permissions',$role->id)}}"><i class="fa fa-cogs"></i></a>
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
