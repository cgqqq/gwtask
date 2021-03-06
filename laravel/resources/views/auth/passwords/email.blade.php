{{--密码寻回页面--}}
@extends('layouts.index')
@section('icon')
@stop
@section('content')
    @unless(empty($res))
        <input type="hidden" value="{{$res}}" id="msg">
        <script>
            window.onload = function(){
                layui.use('layer', function(){
                    var msg=$('#msg').val();
                    if(msg=='1'){
                        layer.msg('Email has been sent successfully!');
                    }
                });
            }
        </script>
    @endunless
    <div class="main-panel">
        <div class="sec-panel">
            <div style="width: 90%;height:80%;margin-left: 50px;">
                <p style="font-size: 50px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;text-decoration: none;"> {{trans('Mail/email.1')}}</p>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal" method="POST" action="{{ url('mail/email')}}" style="margin-top: 100px;">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label"> {{trans('Mail/email.2')}}</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email_address" value="{{ old('email') }}" required >

                            @unless(empty($res))
                                @unless($res=='1')
                            <span style="font-size: 12px;color: #b9151b">{{trans('Mail/email.3')}}</span>
                                @endunless
                            @endunless
                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button id="submit" type="submit" class="layui-btn shadow submit" style="border: 2px solid #0C0C0C;color: #0C0C0C;margin-top: 30px;float: right">
                                {{trans('Mail/email.4')}}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
