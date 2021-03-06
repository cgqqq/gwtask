<!DOCTYPE html>
<html >

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
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<!-- <script src="{{ asset('js/') }}" type="text/javascript"></script> -->
    <script src="{{ asset('layui.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <style>
        body .a-class .layui-layer-btn .layui-layer-btn0{background:#34bf49;border: 1px solid #34bf49}
    </style>


</head>
<body>


<div id="app">

    <div id="home" class="banner" style="min-height:800px;height:auto; ">
        <div class="container" >
            {{--个人中心以及登出的icons--}}
            <div class="icons newA" style="margin-top: 0;">
                @section('icon')
                    <ul>
                        @if(session('news'))
                            <li><a href="{{ url('user/displayInfo') }}" class="center_icon2"> </a></li>
                        @else
                            <li><a href="{{ url('user/displayInfo') }}" class="center_icon"> </a></li>
                        @endif
                        <li><a href="javascript:void(0)" class="logout_icon"> </a></li>
                            @foreach (Config::get('app.locales') as $lang => $language)
                                @if ($lang != App::getLocale())
                                    <li style="font-weight: 800;font-family: 微软雅黑;position: relative;bottom: 15px" class="ec"><a href="{{ route('lang.change', $lang) }}">{{$language}}</a>
                                    </li>
                                @endif
                            @endforeach

                    </ul>
                @show
            </div>
            {{--网页LOGO--}}
            <div class="logo" style="margin-bottom: 0;margin-left:420px;">

                @if (session('user_name')==null)

                    <a href="{{ url('/') }}" style="font-weight: 900; "><img src="{{URL::asset('/images/logo.png')}}" alt=" " >DINO</a>

                @else
                    <a href="{{ url('home') }}" style="font-weight: 900; "><img src="{{URL::asset('/images/logo.png')}}" alt=" " >DINO</a>

                @endif
            </div>

            <div class="main-panel2" style="display:flex;min-height: 630px;height:auto;width: 100%;padding-left:0;background-color: transparent;">
                {{--Layui的导航--}}
                <ul class="layui-nav"  style="width: 100%;height: 100%;background-color:#34bf49;">{{trans('layouts/home.1')}}
                    <li class="layui-nav-item"><a href="{{url('home')}}">{{trans('layouts/home.1')}}</a></li>
                    <li class="layui-nav-item">
                        <a href="{{url('team/displayMine/default')}}">{{trans('layouts/home.2')}}</a>
                        <dl class="layui-nav-child">
                            <dd><a href="{{url('team/displayMine/default')}}">{{trans('layouts/home.3')}}</a></dd>
                            <dd><a href="{{url('team/displayMineCre/default')}}">{{trans('layouts/home.4')}}</a></dd>
                            <dd><a href="{{url('team/displayAdd')}}">{{trans('layouts/home.5')}}</a></dd>
                        </dl>
                    </li>
                    <li class="layui-nav-item"><a href="{{url('task/displayAll')}}">{{trans('layouts/home.6')}}</a>
                       {{-- <dl class="layui-nav-child">
                            <dd><a href="{{url('')}}">Tasks In Charged</a></dd>
                            <dd><a href="{{url('')}}">Tasks Published By Me</a></dd>
                        </dl>--}}
                    </li>
                {{--    <li class="layui-nav-item">
                        <a href="{{url('team/displayAll')}}">All Teams</a>
                    </li>
                    <li class="layui-nav-item">
                        <a href="{{url('user/displayAll')}}">All Users</a>
                    </li>
                    <li class="layui-nav-item"><a href="#">Others</a></li>--}}


                    <div class="search_input2" style="width: 450px;float: right;margin-top: 15px">

                        @if(empty($_COOKIE['flag']) or $_COOKIE['flag']==1)
                            <button type="button" id="search_user" class="search_btn">
                            </button>
                            <form style="background-color: #34bf49;border-radius:40px;margin-right: 0;" method="post" action="{{url('user/displaySearchResult')}}">
                                {{ csrf_field() }}

                                <input name='key' id="key" type="text" placeholder="{{trans('layouts/home.7')}}" id="placeholder" >


                            </form>
                        @else
                            <button type="button" id="search_team" class="search_btn">
                            </button>
                            <form style="background-color: #34bf49;border-radius:40px;margin-right: 0;" method="post" action="{{url('team/displaySearchResult')}}">
                                {{ csrf_field() }}
                                <input name='key' id="key" type="text" placeholder="{{trans('layouts/home.8')}}" id="placeholder" >


                            </form>

                        @endif
                    </div>

                    {{--主要内容区域--}}
                    <div style="background-color:#fcfcfc;width:1100px;min-height:900px;height:auto;margin-left: 0px;margin-right: 0px; padding-top:35px;padding-left:30px;  " class="shadow box">
                        @section('content')

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
                <h3>{{trans('layouts/home.9')}}</h3>

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
    $(function(){
        layui.use('layer',function(){
            $('.icons').on('click','.logout_icon',function(){
                //配置一个透明的询问框
                layer.msg('<span style="font-weight: 700">Are you sure to log out ?</span>', {
                    anim:6
                    ,time: 0 //不自动关闭
                    ,btn: ['yes', 'no']
                    ,skin:'a-class'
                    ,btnAlign: 'c'
                    ,btn1:function(index,layero){
                        window.location.href="{{url('user/logout')}}";
                    }
                    ,btn2:function(index){
                        layer.close(index);
                    }
                });


            });
        });
    });
    $(".search_btn").mousedown(function(){
        var id = document.getElementById('search_team');
        if(id!=null){
            setCookie('flag',1);
            location.reload();

        }
        else{
            setCookie('flag',2);
            location.reload();
        }
    });


    /*对cookie的操作*/
    /*修改cookie*/
    function setCookie(name,value)
    {
        var Days = 30;
        var exp = new Date();
        exp.setTime(exp.getTime() + Days*24*60*60*1000);
        document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
    }

    //读取cookies
    function getCookie(name)
    {
        var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");

        if(arr=document.cookie.match(reg))

            return unescape(arr[2]);
        else
            return null;
    }
</script>