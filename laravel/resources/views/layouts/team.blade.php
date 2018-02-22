<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>任务管理系统</title>

    <link rel="stylesheet" href="{{ asset('css/layui.css') }}"  media="all">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/layout.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/Mycss.css') }}">

    <script src="{{ asset('js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('layui.js') }}" charset="utf-8"></script>
    <script type="text/javascript">
        $.ajaxSetup({
             headers: {
                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
</head>

<body>
    <div class="container">
        <div id="header">
            @yield('header')
            <a href="{{ url('home') }}">主页</a>
            <img src="{{ asset(session('user_profile')) }}" height="100" width="100" style="float: right;margin-right: 20px;">
        </div>

        <div id="middle">
            <div id="menu">
                @yield('menu')
                <ul>
                <li><a href="{{  url('team/displayMine')  }}">我的团队</a></li>
                <li><a href="{{  url('team/displayAll')  }}">所有团队</a></li>
                <li> <a href="{{  url('team/displayAdd')  }}">添加团队</a></li>               
                <li><a href="{{ url('user/logout') }}">退出登录</a></li>
                </ul>

            </div>

            <div id="content">
             @yield('content')
         </div>
     </div>

     <div id="footer">
        @yield('footer')
    </div>

</div>
</body>

</html>