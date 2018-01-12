{{--密码寻回页面--}}
@extends('layouts.index')
@section('icon')
@stop
@section('content')
    <div class="main-panel">
        <div class="sec-panel">
            <div style="width: 90%;height:80%;margin-left: 50px;">
                <p style="font-size: 50px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;text-decoration: none;">Password Retrival</p>
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="form-horizontal" method="POST" action="{{ route('password.email') }}" style="margin-top: 100px;">
                    {{ csrf_field() }}

                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                        <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                        <div class="col-md-6">
                            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                            <button type="submit" id="submit" class="layui-btn layui-btn-radius shadow" style="margin-top: 25px">
                                <a>Send Password Reset Link</a>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection
