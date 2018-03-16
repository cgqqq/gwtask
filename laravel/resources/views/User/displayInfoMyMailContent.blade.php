@extends('User.displayInfo')


@section('displayInfo_content')
    <div style="width: 680px;height: 40px;" id="profile"  class="newA" >
        <a href="{{url('user/displayInfoMailBox')}}">
        <img class="layui-circle" style="border: 1px solid black;margin-left: 20px;margin-top: 10px" src="{{URL::asset('/images/back.png')}}" >
        </a>
    </div>
    <div style="height: 460px;width: 680px;padding: 30px">
        <div id="mail_content" style="color: black;font-size: 15px;width: 620px;height: 375px;font-weight: 600;border: 2px dotted black">
            <div style="margin: 16px;">
            <span style="color: #8D8D8D">
             To :
            </span>{{$mail_to_info['user_name']}}</div>
                <div style="margin-left: 16px;margin-top: 10px;width: 580px;height: 30px;" class="scroll"><span style="color:#8D8D8D;">Subject : </span>{{$mail_content['mail_title']}}</div>
            <div style="margin-left:16px;margin-top: 10px;width: 580px;height: 280px; word-wrap:break-word;" class="scroll"><span style="color:#8D8D8D;">Content : </span>{{$mail_content['mail_content']}}</div>
        </div>
    </div>
@endsection