@extends('backend.layouts.admin')

@section('bread')
    Products
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Asssign permissions to roles</h3>
                    <div class="box-tools">
                        <a href="{{route('backend.roles.create')}}">create new</a>
                    </div>
                </div>
                <div class="box-body">

                    <form action="{{route('backend.roles.store_assign')}}" method="post">
                        @csrf
                        <input type="hidden" name="role_id" value="{{$role->id}}">
                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Display name</th>
                                <th>Description</th>
                                <th>Ability</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$permission->name}}</td>
                                    <td>{{$permission->display_name}}</td>
                                    <td>{{$permission->description}}</td>
                                    <td>
                                        <input type="checkbox" name="permissions[]" value="{{$permission->id}}" {{$role->hasPermission($permission->name)?"checked":""}}>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <button class="btn btn-success" type="submit">Update</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

@endsection
