<!DOCTYPE html>
<html>
<head>
    <title>Zuru</title>

    <!--//theme-style-->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Soko Rahisi, Kenyan online market, Kenya business online, Sell Online,Soko, Rahisi" />
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->

    <link rel="shortcut icon" href="{{asset('img/logo.png')}}">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    {{--    <link href="{{asset('css/frontend.css')}}" rel="stylesheet" type="text/css" media="all" />--}}
    @yield('style')

    <style>

        @font-face {
            font-family: "Raleway Bold";
            src: url(fonts/raleway/Raleway-Bold.ttf);
        }


        @font-face {
            font-family: "Raleway Regular";
            src: url(fonts/raleway/Raleway-Regular.ttf);
        }

        .bg_primary{
            background-color:#FF5722 !important;
            color:white !important;
        }

        .bg_accent{
            background-color:#8BC34A !important;
            color:white !important;
        }
        .bg_primary a{
            color:white !important;
        }

        .footer {
            bottom: 0;
            width: 100%;
            height: 60px;
            line-height: 60px;
            background-color: #FFCCBC;
        }

        .regular_font{
            font-family: "Raleway Regular"!important;
        }
        .bold_font{
            font-family: "Raleway Bold"!important;
        }

        .container {
            height: 100%;
            z-index: 1;
            text-align: center;
            position: relative;
        }

        header{
            background-image: url("img/bg1.png");
            background-repeat: no-repeat;
            background-size: cover;
        }
        .pricing{
            text-align: left;
            padding: 10px;
        }
        .pricing h4{
            color: #8BC34A;
        }
        .pricing .percent{
            color:#E64A19;
            font-weight: bold;
            font-size: large;
        }
        .pricing p{
            font-size: small;
        }

    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light bg_primary">
    <a class="navbar-brand" href="{{url('/')}}">
        <img src="{{asset('zuru logo symbol.png')}}" width="30" height="30" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Feedback</a>
            </li>
        </ul>

    </div>
</nav>

<div style="margin-bottom: 70px">
    <header>
        <div class="container">

            <div class="row mt-5 header">
                <div class="col-sm-6 text-center">
                    <div class="row">
                        <div class="col-sm-8 mx-auto">
                            <img class="img img-fluid d-none d-lg-block d-md-block" src="{{asset('zuru logo symbol.png')}}">
                            <img class="img img-fluid d-sm-block d-md-none" src="{{asset('zuru logo symbol.png')}}">

                            <p class="bold_font">"Our narative is revolutionslise how small markets operate, share  information and interact with their customers</p>
                            <br>

                            <div class="pricing">
                                <h4 class="bold_font"><u>Explore your surroundings from your phone!!</u></h4>
                                <p class="regular_font mt-3">As a zuru user, you know the drill <a href="">Terms and conditions</a></p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-sm-6">
                    <div class="row">
                        <div class="col-sm-8 mx-auto text-center">
                            <img src="{{ asset('img/device-2019-08-24-162725.png') }}" alt="" class="img-fluid">
                            <br>
                            <h4 class="bold_font">Get the app on playstore</h4>
                            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Get_it_on_Google_play.svg/1280px-Get_it_on_Google_play.svg.png" alt="" width="150px">
                        </div>
                    </div>

                </div>
            </div>



        </div>

    </header>

    {{--//the services section--}}
    <section class="mt-5">

        <div class="container">
            <div class="row">
                <div class="col-sm">
                    <img src="{{asset('img/globalVillage.png')}}" width="150px" class="img img-fluid" alt="">
                    <h4 class="bold_font">Global Village</h4>
                    <p class="regular_font">Request for a product or service you need and get feedback from soko rahisi business directory. All soko rahisi aproved businesses are secure</p>
                    {{--<a href="" class="btn btn-primary"><i class="fa fa-code"></i> Playground</a>--}}
                </div>
                <div class="col-sm">
                    <img src="{{asset('img/smallScaleBusinesses.png')}}" width="150px" class="img img-fluid" alt="">

                    <h4 class="bold_font">Small scale Businesses</h4>
                    <p class="regular_font">Zuru provides the best platform for you to start your business online. Sell your goods from the comfort of your house</p>
                    {{--<button class="btn btn-warning"><i class="fa fa-share-alt"></i> Download app</button>--}}

                </div>
                <div class="col-sm">
                    <img src="{{asset('img/paymentProcessing.png')}}" width="150px" class="img img-fluid" alt="">

                    <h4 class="bold_font">Payment processing</h4>
                    <p class="regular_font">Zuru is provides powerful payments providing both merchants and purchasers of secure fraud free payments</p>
                    {{--<button class="btn btn-warning"><i class="fa fa-money"></i> Coming soon</button>--}}
                </div>
            </div>
        </div>

    </section>





</div>

<footer class="footer mt-5 bg_accent">
    <div class="container">
        <span class="regular_font">Zuru &copy; {{date('Y')}} <a class="text-white" href="">Neverest LTD</a></span>
    </div>
</footer>

@yield('script')
</body>

</html>