{{--注册页面--}}
@extends('layouts.index')

@section('icon')
@stop
@section('content')
    <div class="main-panel">
        <div class="sec-panel">
            <div style="width: 90%;height:80%;margin-left: 50px;">
                {{--注册信息填写表格--}}
                <p style="font-size: 50px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;">REGISTER</p>
                <form class="form-horizontal" method="POST" action="{{ url('register/add') }}" enctype="multipart/form-data" >
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                        <label for="user_id" class="col-md-4 control-label">User ID</label>

                        <div class="col-md-6">
                            <input id="user_id" type="text" class="form-control" name="user_id" value="{{ old('user_id') }}" required autofocus>

                            @if ($errors->has('user_id'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('user_id') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                        <label for="user_name" class="col-md-4 control-label">User Name</label>

                        <div class="col-md-6">
                        <!-- 姓名不允许输入空格 -->
                            <input id="user_name" type="text" class="form-control" name="user_name" value="{{ old('user_name') }}" required on="this.value=this.value.replace(/[^\w]/g,'');">

                            @if ($errors->has('user_name'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('user_name') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('user_email') ? ' has-error' : '' }}" >
                        <label for="user_email" class="col-md-4 control-label">E-mail Address</label>

                        <div class="col-md-6" id="emial_box" style="display: inline-flex" >
                            <input id="user_email" type="email" class="form-control" name="user_email" value="{{ old('user_email') }}" required onblur="checkEmail()">
                            <span id='wrong' style="float: left;margin: 5px;display: none"><img src="{{URL::asset('/images/wrong.png')}}"></span>
                            <span id='correct' style="float: left;margin: 5px;display: none"><img src="{{URL::asset('/images/correct.png')}}"></span>
                            @if ($errors->has('user_email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('user_email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group{{ $errors->has('user_password') ? ' has-error' : '' }}">
                        <label for="user_password" class="col-md-4 control-label">Password</label>

                        <div class="col-md-6">
                            <input id="user_password" type="password" class="form-control" name="user_password" required>

                            @if ($errors->has('user_password'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('user_password') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                        <div class="col-md-6">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required onblur="validate()">
                        </div>
                    </div>


                    <div class="form-group{{ $errors->has('user_profile') ? ' has-error' : '' }}">
                        <label for="user_profile" class="col-md-4 control-label">Profile</label>

                        <span class="col-md-6">
                        <div class="aaa" >

             <span class="btn btn-success fileinput-button" style="background: #fff200;border-color: #fff200;">
            <span style="font-weight: 700;">Upload Image</span>
            <input id="user_profile" type="file" class="" name="user_profile" required >
             </span>
                        </div>

                            @if ($errors->has('user_profile'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('user_profile') }}</strong>
                                    </span>
                        @endif
                        </span>
                    </div>


                    <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button id="submit" type="submit" class="layui-btn shadow" style="border: 2px solid #0C0C0C;" >
                        <a style="color: #0C0C0C;"> REGISTER</a>
                    </button>
                </div>
            </div>
            </form>
            </div>

        </div>
    </div>

@endsection
<script>
    function validate(){
        var pwd1 = document.getElementById("user_password").value;
        var pwd2 = document.getElementById("password-confirm").value;
        if(pwd1 != pwd2) {
            layui.use('layer', function(){
                layer.msg('The two passwords ' +
                    'you have entered are inconsistent !');
                document.getElementById("submit").disabled = true;
            });
        }
        else{
            document.getElementById("submit").disabled = false;
        }
    }
    var send=document.getElementById("submit");
    send.onclick=function(){
        var file=document.getElementById("user_profile").value;
        if(file.length<1){
            alert('Please Upload Your Profile');
            return false;
        }
    }
    function checkEmail(){
        layui.use('layer', function(){
            var email = $('#user_email').val();
            var flag='1';
            $.ajax({
                url: "{{ url('register/display') }}",
                type: 'post',
                dataType: 'json',
                data: {"email": email,'flag':flag, "_token": "{{csrf_token()}}"}
            })
                .done(function (data) {
                    var c = document.getElementById('correct');
                    var w = document.getElementById('wrong');
                    if (data.msg == '1') {
                        layer.msg("This email address is available !");
                        document.getElementById("submit").disabled = false;
                        w.style.display = 'none';
                        c.style.display = 'block';
                    } else {
                        layer.msg(data.msg);
                        document.getElementById("submit").disabled = true;
                        c.style.display = 'none';
                        w.style.display = 'block';
                    }

                })
                .fail(function () {
                    layer.msg("Busy Network,try again later!");
                })
                .always(function () {
                    console.log("complete");
                });
        });    }
</script>