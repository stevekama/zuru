@extends('backend.layouts.admin')

@section('bread')
    Users management
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title">Users list</h3>
                </div>
                <div class="box-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Login mode</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$user->name}}</td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @if($user->login_mode ==0)
                                        <span class="label label-default">customer</span>
                                    @elseif($user->login_mode ==1)
                                        <span class="label label-default">vendor</span>
                                    @elseif($user->login_mode ==2)
                                        <span class="label label-default">rider</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->login_mode ==1)
                                        <a  href="{{route('backend.users.vendor_information',$user->id)}}" class="label label-success">shop</a>
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="box">
                <div class="box-header">
                    <div class="box-title">Vendor information</div>
                </div>
                <div class="box-body">
                    <form action="" ></form>
                </div>
            </div>
        </div>
    </div>

    @endsection
