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
    <link href="{{ asset('css/layui.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" media="all" >
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all" >
 {{--   <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" media="all" >--}}
    <link href="{{ asset('https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800') }}" rel="stylesheet" type="text/css" media="all" >
    <link href="{{ asset('https://fonts.googleapis.com/css?family=Source+Sans+Pro:200,300,400,600,700,900,200italic,300italic,400italic,600italic,700italic,900italic') }}" rel="stylesheet" type="text/css" media="all" >
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('layui.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>


</head>
<body>


<div id="app">

    <div id="home" class="banner" style="min-height:800px;height:auto; ">
        <div class="container" >
            {{--个人中心以及登出的icons--}}
            <div class="icons" style="margin-top: 0; ">
                @section('icon')
                    <ul>
                        <li><a href="{{ url('user/displayInfo') }}" class="center_icon"> </a></li>
                        <li><a href="{{url('user/logout')}}" class="logout_icon"> </a></li>

                    </ul>
                @show
            </div>
            {{--网页LOGO--}}
            <div class="logo" style="margin-bottom:0;margin-left: auto;">
                <a href="{{ url('/') }}"><img src="{{URL::asset('/images/logo.png')}}" alt=" " >DINO</a>
            </div>

            <div class="main-panel2" style="display:flex;min-height: 630px;height:auto;width: 100%;padding-left:0;background-color: transparent;">
                {{--Layui的导航--}}
                <ul class="layui-nav"  style="width: 100%;height: 100%;background-color:#34bf49;">
                    <li class="layui-nav-item"><a href="{{url('Home/home')}}">Home</a></li>
                    <li class="layui-nav-item">
                        <a href="#">Teams</a>
                        <dl class="layui-nav-child">
                            <dd><a href="{{url('team/displayMine')}}">Mine Teams</a></dd>
                            <dd><a href="{{url('team/displayAdd')}}">Create One</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item"><a href="#">Tasks</a></li>
                    <li class="layui-nav-item">
                        <a href="{{url('team/displayAll')}}">All Teams</a>
                    </li>
                    <li class="layui-nav-item">
                        <a href="{{url('user/displayAll')}}">All Users</a>
                    </li>
                    <li class="layui-nav-item"><a href="#">Others</a></li>
                    {{--主要内容区域--}}
                    <div style="background-color:#fcfcfc;width:1100px;min-height:800px;height:auto;margin-left: 0px;margin-right: 0px; padding-top:35px;padding-left:30px;  " class="shadow">
                        @section('content')

                          首页未设计
                        @show
                    </div>
                </ul>
            </div>
        </div>
    </div>


    <!-- footer -->
    <div class="footer">
        @yield('foot')
        <div class="container">
            <div style="margin-bottom: 70px">
                <h3>BLAHBLAHBLAHBLAHBLAH AND BLAH</h3>

            </div>
            <p style="text-align: center">Copyright &copy; 2018 Graduation Project-Chen Guo Qing & Peng Wen Shu from GDUFS 任务管理系统</p>
        </div>
    </div>
</div>

</body>
</html>
@yield('script')
<script>
    layui.use('element', function(){
        var element = layui.element(); //导航的hover效果、二级菜单等功能，需要依赖element模块

        //监听导航点击
        element.on('nav(demo)', function(elem){
            alert(elem.text());
            layer.msg(elem.text());
        });
    });
</script>