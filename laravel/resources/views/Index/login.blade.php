{{--用户登录页面--}}
@extends('layouts.index')
@section('icon')
@stop
@section('content')
<div class="main-panel">
    <div class="sec-panel">
        <div style="width: 90%;height:80%;margin-left: 50px;">
            {{--用户登录信息填写表格--}}
            <p style="font-size: 50px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;text-decoration: none;">{{trans('Index/login.1')}}</p>
            <form class="form-horizontal" style="margin-top: 70px;">
                {{ csrf_field() }}

                <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                    <label for="user_id" class="col-md-4 control-label">{{trans('Index/login.2')}}</label>

                    <div class="col-md-6">
                        <input id="user_id" type="text" class="form-control" name="user_id" value="{{ old('user_id') }} " required autofocus>

                        @if ($errors->has('user_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('user_id') }}</strong>
                        </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('user_password') ? ' has-error' : '' }}">
                    <label for="user_password" class="col-md-4 control-label">{{trans('Index/login.3')}}</label>

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
                    <div class="col-md-6 col-md-offset-4">
                        <div class="drag" style="">
                            <div class="bg"></div>
                            <p class="text">{{trans('Index/login.4')}}</p>
                            <div class="btn1"></div>
                        </div>

                    </div>
                </div>



                <div class="form-group">
                    <div class="col-md-8 col-md-offset-4">
                        <button id="submit" type="submit" class="layui-btn shadow" style="border: 2px solid #0C0C0C;">
                            <a style="color: #0C0C0C">{{trans('Index/login.5')}}</a>
                        </button>
                        <a class="link"  href="{{ route('password.request') }}">
                            {{trans('Index/login.6')}}
                        </a>
                    </div>
                </div>
            </form>

        </div>

    </div>
</div>
@endsection

@section('script')
<script>
    var $ = function(selector){
        return document.querySelector(selector);
    },
            box = $('.drag'),//容器
            bg = $('.bg'),//绿色背景
            text = $('.text'),//文字
            btn = $('.btn1'),//拖动按钮
            done = false;//是否通过验证

            btn.onmousedown = function(e){
                var e = e || window.event;
                var posX = e.clientX - this.offsetLeft;

                btn.onmousemove = function(e){
                    var e = e || event;

                    var x = e.clientX - posX;

                    this.style.left = x + 'px';
                    bg.style.width = this.offsetLeft + 'px';

                // 通过验证
                if(x >= box.offsetWidth - btn.offsetWidth){
                    done = true;
                    btn.onmousedown = null;
                    btn.onmousemove = null;
                    text.innerHTML = 'Succeeded';
                    $('#submit').click()
                }
            };

            document.onmouseup = function(){
                btn.onmousemove = null;
                btn.onmouseup = null;

                if(done)return;
                btn.style.left = 0;
                bg.style.width = 0;
            }
        };

        text.onmousedown = function(){
            return false;
        }

        // dom文档完成加载后
        jQuery(document).ready(function($) {
            layui.use('layer',function(){
                $('#submit').on('click',function(event){
                    event.preventDefault();
                    var user_id=$('#user_id').val();
                    var user_password=$('#user_password').val();
                    if($('.text').text()=='Succeeded'){
                        if(user_id&&user_password){
                            $.ajax({
                                url: "{{ url('user/login') }}",
                                type: 'post',
                                dataType: 'json',
                                data: {'user_id':user_id,'user_password':user_password,"_token":"{{csrf_token()}}"},
                            })
                            .done(function(data) {
                                if(data.flag){
                                    layer.load(1, {
                                        shade: [0.1,'#fff'] //0.1透明度的白色背景
                                    });
                                    setTimeout(function(){
                                        layer.closeAll('loading');
                                        window.location.href="{{ url('home') }}";
                                    }, 750);
                                }else{
                                    alert('login failed');
                                    setTimeout("location.reload()");
                                }
                            })
                            .fail(function() {
                                console.log("error");
                            })
                            .always(function() {
                                console.log("complete");
                            });
                        }else{

                            alert('请输入用户名和密码！');
                            location.reload();
                        }
                    }else{
                        alert('请先通过验证！');
                    }
                });
            });
            

        });
    </script>
    @stop