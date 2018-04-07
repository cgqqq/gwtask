{{--密码寻回页面--}}
@extends('layouts.index')
@section('icon')
@stop
@section('content')
    <div class="main-panel">
        <div class="sec-panel">
            <div style="width: 90%;height:80%;margin-left: 50px;">
                <p style="font-size: 50px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;text-decoration: none;">Reset Password</p>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal reset_form" style="margin-top: 100px;">
                    {{ csrf_field() }}

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

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button id="submit" type="submit" class="layui-btn shadow submit" style="border: 2px solid #0C0C0C;color: #0C0C0C;margin-top: 30px;float: right">
                                Reset Password
                            </button>
                            <input type="hidden" name="user_id" value="{{$user_id}}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
    $(function(){
        layui.use('layer', function(){
            $('.reset_form').on('click','#submit',function(event){
                event.preventDefault();
                var id = $(this).siblings("[name='user_id']").val();
                var password=$('#password-confirm').val();
                $.ajax({
                    url: "{{ url('mail/resetPassword') }}",
                    type: 'post',
                    dataType: 'json',
                    data: {'user_id':id,"password":password,"_token":"{{csrf_token()}}"}
                })
                    .done(function(data) {
                        layer.msg(data.msg);
                        window.location.href="{{url('login1')}}";

                    })
                    .fail(function(data) {
                        layer.msg(data.msg);
                    })
                    .always(function() {
                        console.log("complete");
                    });
            });

        });
    });
</script>


@endsection
