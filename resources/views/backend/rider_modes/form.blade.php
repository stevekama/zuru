@extends('backend.layouts.admin')

@section('bread')
    Vendor categories
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Manage rider mode</h3>
                    <div class="box-tools">
                        <a href="{{route('backend.rider_modes.list')}}">back to list</a>
                    </div>
                </div>
                <div class="box-body">
                    <form action="{{route('backend.rider_modes.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" value="{{old('id')}}"></input>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Mode name</label>
                                    <input type="text" name="name" value="{{old('name')}}" class="form-control">
                                    <span class="text-danger">{{($errors->has('name'))?$errors->first('name'):""}}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="">Rider cost per km</label>
                                    <input type="text" name="price_per_km" value="{{old('price_per_km')}}" class="form-control">
                                    <span class="text-danger">{{($errors->has('price_per_km'))?$errors->first('price_per_km'):""}}</span>
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
                                    <label for="">Maximum available distance</label>
                                    <input type="text" name="max_available_distance" value="{{old('max_available_distance')}}" class="form-control">
                                    <span class="text-danger">{{($errors->has('max_available_distance'))?$errors->first('max_available_distance'):""}}</span>
                                </div>
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