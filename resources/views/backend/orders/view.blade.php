@extends('backend.layouts.admin')

@section('bread')
    Orders
@endsection

@section('style')
    <style type="text/css">
        #map {
            height: 400px;
        }
        #navlist li
        {
            width: 100px;
            height:100px;
            border: 1px solid green;
            display: inline;
            list-style-type: none;
            padding-right: 20px;
        }
        .price{
            font-size: 36px;
            font-weight: bold;
            color: orange;
            text-align: center;
        }
        .price-status{
            text-align: center;
        }
        .distance{
            font-size: 36px;
            font-weight:bold;
        }
        .distance-label{
            padding: 10px;
        }
        .delivery-status {
            min-height: 100px;
            background: orange;
            height: 90px;
            line-height: 90px;
            text-align: center;
            color: white;
        }
        .status-text{
            font-weight:bold;
            font-size:large ;
        }
    </style>
@endsection


@section('content')

    <div class="row" id="parcels-tracking">

        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4" style=" border-right: 1px solid lightgrey;padding: 10px 25px 10px 25px">

                            <strong><i class="fa fa-cart"></i>Title</strong>
                            <p class="text-muted">{{$order->order_no}}</p>
                            <hr>

                            <strong><i class="fa fa-cart"></i>Location</strong>
                            <p class="text-muted">{{$order->location}}</p>
                            <hr>

                            <div class="row">
                                <div class="col-sm-6">
                                    <strong><i class="fa fa-cart"></i>Phone</strong>
                                    <p class="text-muted">{{$order->weight}}</p>
                                </div>
                                <div class="col-sm-6">
                                    <strong><i class="fa fa-cart"></i>status</strong>
                                    <p class="text-muted">{{$order->status}}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <strong><i class="fa fa-cart"></i>Amount</strong>
                                    <p class="text-muted">{{$order->items()->sum('selling_price')}}</p>
                                </div>
                                <div class="col-sm-6">
                                    <strong><i class="fa fa-cart"></i>Created at</strong>
                                    <p class="text-muted">{{$order->created_at->diffForHumans()}}</p>
                                </div>
                            </div>


                            <hr>

                            <div class="row">
                                <div class="col-sm-12">
                                    <p class="price">KSH. {{$order->cost}}</p>
                                    <p class="price-status">
                                        <label for="" class="label label-default">unpaid</label>
                                    </p>
                                </div>
                            </div>


                            <hr>
                            <h3>Buyer</h3>
                            @php($buyer = $order->buyer)
                            @if($buyer!=null)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <img src="http://www.gravatar.com/avatar/64e1b8d34f425d19e1ee2ea7236d3028.jpg?s=80&amp;d=mm&amp;r=g" class="img-circle" alt="User Avatar">
                                        <a class="users-list-name" href="#">{{$buyer->name}}</a>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>{{$buyer->name}}</p>
                                        <p>{{$buyer->email}}</p>
                                        <p>{{$buyer->created_at->diffForHumans()}}</p>
                                    </div>
                                </div>
                            @endif


                        </div >

                        <div class="col-md-8" id="tracking_vue" >

                            <div id="map"></div>
                            <h3>Order items</h3>

                            <hr>

                            <ul class="products-list product-list-in-box">
                                @foreach($order->items as $item)
                                    @php($product = $item->product()->withTrashed()->first())
                                    @if($product!=null)
                                        <li class="item">
                                            <div class="product-img">
                                                <img src="{{($product->avatar==null)?asset('dist/img/default-50x50.gif'):asset("files/items/" . $product->avatar)}}" alt="Product Image">
                                            </div>
                                            <div class="product-info">
                                                <a href="javascript:void(0)" class="product-title">{{$product->name}}
                                                    <span class="label label-warning pull-right">KSH {{$item->selling_price}}</span></a>
                                                <span class="product-description">
                                                  {{$product->description}}
                                                </span>
                                            </div>
                                        </li>
                                    @endif
                                    <!-- /.item -->
                                    @endforeach
                            </ul>

                            <hr>

                            <h3>Rider</h3>

                            @php($ride = $order->rides()->first())
                            @if($ride!=null)
                                @php($rider=$ride->rider->user)
                                <div class="row">
                                    <div class="col-sm-6">
                                        <img src="http://www.gravatar.com/avatar/64e1b8d34f425d19e1ee2ea7236d3028.jpg?s=80&amp;d=mm&amp;r=g" class="img-circle" alt="User Avatar">
                                        <a class="users-list-name" href="#">{{$rider->name}}</a>
                                    </div>
                                    <div class="col-sm-6">
                                        <p>{{$rider->name}}</p>
                                        <p>{{$rider->email}}</p>
                                        <p>{{$rider->created_at->diffForHumans()}}</p>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <p>Order has not been assigned a ride yet!</p>
                                </div>
                            @endif

                            <hr>




                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('script')

    <script>
        function initMap() {
            var myLatLng = {lat: -1.363, lng: 31.044};
            window.map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
                center: myLatLng
            });
            @foreach($order->vendors() as $vendor)
                var myLatLng = {lat: {{$vendor->latitude}}, lng: {{$vendor->longitude}}};
                var marker = new google.maps.Marker({
                position: myLatLng,
                map: map,
                title: '{{$vendor->shop_name}}'
                });

                @if($loop->last)
                map.setCenter({lat: {{$vendor->latitude}}, lng: {{$vendor->longitude}}});
                    @endif
            @endforeach
        }
    </script>
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD8YU48m7RJufra0o6ePuY2FwMX5AMZ2EU&callback=initMap">
    </script>
    <script type="application/javascript">

    </script>
@endsection
