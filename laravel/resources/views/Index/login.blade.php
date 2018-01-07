@extends('layouts.index')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">登录页面</div>

                <div class="panel-body">


                    <form class="form-horizontal" method="POST" action="{{ url('user/login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('user_id') ? ' has-error' : '' }}">
                            <label for="user_id" class="col-md-4 control-label">用户ID</label>

                            <div class="col-md-6">
                                <input id="user_id" type="text" class="form-control" name="user_id" value="{{ old('user_id') }}" required autofocus>

                                @if ($errors->has('user_id'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('user_id') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('user_password') ? ' has-error' : '' }}">
                            <label for="user_password" class="col-md-4 control-label">密码</label>

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
                                    <p class="text">拖动滑块验证</p>
                                    <div class="btn1"></div>
                                </div>      

                            </div>
                        </div>

                        

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-4">
                            <button id="submit" type="submit" class="btn btn-primary">
                                    Login
                                </button>

                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    Forgot Your Password?
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('foot')
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
                                        text.innerHTML = '通过验证';
                                        $('#submit').click();
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
                            // jQuery(document).ready(function($) {
                            //     $('#submit').on('click',function(event){
                            //         event.preventDefault();
                            //         if($('p').text()==='通过验证'){
                            //             $('#submit').off('click');
                            //             $('#submit').click();
                            //         }else{
                            //             alert('请先通过验证');
                            //         }
                            //     });
                            // }); 
                        </script> 
                        @endsection