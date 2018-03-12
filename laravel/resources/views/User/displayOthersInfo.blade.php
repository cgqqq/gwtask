@extends('layouts.home')
@section('content')
<div style="width: 280px;height: 800px;background-color:#4aaf51;float: left">
    <div style="width: 280px;height: 280px;float: left;color:#0C0C0C;" class="shadow">
        <img class="layui-circle" style="height: 150px;width:150px;margin-top: 12%;margin-left: 22%" src="{{URL::asset('/uploads/user_profile/'.$user_info['user_profile'])}}" >
        <p style=" background-color:#fcfcfc;margin-top: 10px;font-weight: 800;font-size: 16px;text-align:center; width:280px;height:20px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;  ">
            {{$user_info['user_name']}}
        </p>

            User ID: {{$user_info['user_id']}}</br>
            Emial Address:  {{$user_info['user_email']}}

    </div>

    <div style="width: 280px;height:520px;float: left">
        <div class="collection" style="margin-top: 20px;width: 280px;padding: 5px;">
            <a href="" class="collection-item" style="font-size: 20px;line-height: 30px">
                Resource Sharings
            </a>
            <a href="{{url('user/displayOthersInfoTeams?user_id=2')}}" class="collection-item" style="font-size: 20px;line-height: 30px">
                Teams
            </a>
            <a href="" class="collection-item" style="font-size: 20px;line-height: 30px">
               Tasks
            </a>

        </div>

    </div>
</div>
<div style="width:770px;height: 800px;background-color: #5e35b1;float: left">
    动态（最近分享了什么项目、资源）
</div>
@endsection