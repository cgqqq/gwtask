<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/layui.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" media="all" >
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all" >
    <link href="{{ asset('https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800') }}" rel="stylesheet" type="text/css" media="all" >
    <link href="{{ asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic') }}" rel="stylesheet" type="text/css" media="all" >
    <script type="text/javascript" src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('layui.js') }}"></script>
    <script type="text/javascript" dsrc="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript" dsrc="{{ asset('js/index.js') }}"></script>

</head>
<body>

<div id="app">

    <div id="home" class="banner">
        <div class="container">

            <div class="icons">
                @section('icon')
                    <ul>
                        <li><a href="{{url('login1')}}" class="log_icon"> </a></li>
                        <li><a href="{{url('register/display')}}" class="reg_icon"> </a></li>

                    </ul>
                @show
            </div>
            @section("logo")

                <div class="logo" style="margin-bottom: 0;margin-left:420px;">
                   @if (session('user_name')==null)


                    <a href="{{ url('/') }}" style="font-weight: 900; "><img src="{{URL::asset('/images/logo.png')}}" alt=" " >DINO</a>

                       @else
                    <a href="{{ url('home') }}" style="font-weight: 900; "><img src="{{URL::asset('/images/logo.png')}}" alt=" " >DINO</a>

                       @endif


                </div>
            @show
            @section('content')
                <div class="banner-info">

                    <h3>Easier Task Division and Management</h3>
                    <p>If you want to be happy,set a goal that commands your thoughts,liberates your energy, and inspires your hopes.</p>
                    <div class="get">
                        <a id="good"class="hvr-shutter-in-horizontal" style="cursor: pointer;" href="{{ url('login1') }}">GET START</a>
                    </div>

                </div>
            @show
        </div>
    </div>


    <!-- footer -->
    <div class="footer">
        @yield('foot')
        <div class="container">
            <div style="margin-bottom: 70px">
                <h3>Contact Us</h3>

            </div>
            <p style="text-align: center">Copyright &copy; 2018 Graduation Project-Chen Guo Qing & Peng Wen Shu from GDUFS 任务管理系统</p>
        </div>
    </div>
</div>

</body>
</html>
@yield('script')
