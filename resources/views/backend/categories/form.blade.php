@extends('backend.layouts.admin')

@section('bread')
    Vendor categories
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Manage category</h3>
                    <div class="box-tools">
                        <a href="{{route('backend.vendor_categories.list')}}">back to list</a>
                    </div>
                </div>
                <div class="box-body">
                    <form enctype="multipart/form-data" action="{{route('backend.vendor_categories.store')}}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{old('id')}}">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Category name</label>
                                    <input type="text" name="name" value="{{old('name')}}" class="form-control">
                                    <span class="text-danger">{{($errors->has('name'))?$errors->first('name'):""}}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Avatar</label>
                                    <input type="file" name="avatar" class="form-control">
                                    <span class="text-danger">{{($errors->has('avatar'))?$errors->first('avatar'):""}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="update" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection