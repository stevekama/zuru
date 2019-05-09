@extends('backend.layouts.admin')

@section('bread')
    Vendor categories
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Manage products</h3>
                    <div class="box-tools">
                        <a href="{{route('backend.products.list')}}">back to list</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{route('backend.products.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{old('id')}}"></input>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Product name</label>
                                    <input type="text" name="name" value="{{old('name')}}" class="form-control">
                                    <span class="text-danger">{{($errors->has('name'))?$errors->first('name'):""}}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Price</label>
                                    <input type="text" name="price" value="{{old('price')}}" class="form-control">
                                    <span class="text-danger">{{($errors->has('price'))?$errors->first('price'):""}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="avatar" class="btn btn-primary"><i class="fa fa-file-image-o"></i> Avatar</label>
                                    <p>All avatars must be of equal length and width</p>
                                    <input id="avatar" type="file" style="display: none" name="avatar" value="{{old('avatar')}}" class="form-control">
                                    <span class="text-danger">{{($errors->has('avatar'))?$errors->first('avatar'):""}}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Vendor</label>
                                    <select class="form-control" name="vendor_id" id="">
                                        @foreach($vendors as $vendor)
                                            <option value="{{$vendor->id}}" {{(old('vendor_id')==$vendor->id)?"selected":""}}>{{$vendor->shop_name}}</option>
                                            @endforeach
                                    </select>
                                    <span class="text-danger">{{($errors->has('vendor_id'))?$errors->first('vendor_id'):""}}</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <label for="">Product description</label>
                                <textarea name="description" id="" class="form-control"></textarea>
                                <span class="text-danger">{{($errors->has('description'))?$errors->first('description'):""}}</span>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for=""></label> <br>
                                    <input type="submit" value="update product" class="btn btn-primary">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endsection