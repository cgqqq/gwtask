@extends('layouts.home')
{{--管理队员--}}
@section('content')
    <div style="width: 1050px;height: 40px;margin-bottom: 30px" id="profile"  class="newA" >

        <img class="layui-circle" style="border: 1px solid black;margin-left: 20px;margin-top: 10px" src="{{URL::asset('/images/back.png')}}"  href="javascript:void(0)"  onclick="javascript:window.history.go(-1);">

    </div>
    <div style="width: 1050px;height: 50px;color: black;margin-bottom: 30px">

    <p style="font-size: 50px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;text-decoration: none;margin-left: 50px;">{{trans('Team/manageTeamMember.1')}}</p>
    </div>
    <div style="float: left;width: 120px;height:700px;margin-left: 30px;">
        <div style="margin-left: 20px;margin-top:50px;width: 40px;display: block" id='profile'>
            <a href="{{url('team/manageTeamMember?team_name='.$team_info['team_name'])}}">
            <img class="layui-circle" src="{{URL::asset('/images/delete.png')}}" >
            </a>
            <a href="{{url('team/manageTeamMemberAdd?team_name='.$team_info['team_name'])}}">
            <img id="add" class="layui-circle" style="height: 50px;width:50px;margin-top: 60px;border: 2px solid black" src="{{URL::asset('/images/add.png')}}">
            </a>
        </div>
    </div>
    <div style="float: left;width: 870px;height: 800px;">
        <table class="layui-table" style="width: 900px;border-style: none;margin-top: 30px;">
   <thead>
       <tr style="font-weight: 800;font-size: 20px;color: #0C0C0C">
           <td style="border-style: none">{{trans('Team/manageTeamMember.2')}}</td>
           <td style="border-style: none">{{trans('Team/manageTeamMember.3')}}</td>
           <td style="border-style: none;overflow: hidden;text-overflow: ellipsis;white-space: nowrap">{{trans('Team/manageTeamMember.4')}}</td>
       </tr>
   </thead>
           <tr>
               <input type="hidden" id="team-id" value="{{ $team_info['team_id'] }}">
               <td id='team-name' style="border-style: none" >{{ $team_info['team_name'] }}</td>
               <td style="border-style: none">{{ $team_info['user_name'] }}</td>
               <td style="border-style: none">{{ $team_info['team_info'] }}</td>
           </tr>
   </table>
   <table class="layui-table table-member" style="width: 900px;">
           <thead>
               <tr>5
                   <td style="border-style: none;width: 200px">
                       <input type="checkbox" id='checkAll' style="position: absolute;top: 12px;"><label for="checkAll" style="position: absolute;top: 15px;left: 35px;cursor: pointer;">{{trans('Team/manageTeamMember.5')}}</label>
                   </td>
                   <td style="border-style: none">{{trans('Team/manageTeamMember.6')}}</td>
                   <td style="border-style: none"><button class="layui-btn layui-btn-danger layui-btn-sm removeAll" style="font-weight: 800">{{trans('Team/manageTeamMember.7')}}</button></td>
               </tr>
           </thead>

           @foreach($pageOut as $member)
               <tr>
                   <input type="hidden" class="user-id" value="{{ $member['user_id'] }}">
                   <td class="user-name" style="border-style: none">
                       <script type="text/javascript">
                           if(String({{$team_info['user_name']}}) != String({{$member['user_name']}})){
                               window.document.write("<input type='checkbox' class='checkOne' style='display:inline-block;position :absolute ; top:12px;'>");
                           }
                           else{
                               window.document.write("<div style='float: right;margin-top: 5px;' >" +
                                   "<img src='{{URL::asset('/images/8.png')}}'>" +
                                   "</div>");
                           }
                       </script>
                       <div style="position: absolute;top: 15px;left: 35px;display: inline-block;" class="newA">
                           @if($member['user_id']!=session('user_id')){{--因为funder才能delete team mate--}}
                           <a href="{{url('user/displayOthersInfo/'.$member['user_id'])}}">{{ $member['user_name'] }}</a>
                       @else
                           <a href="{{url('user/displayInfo')}}">{{ $member['user_name'] }}</a>
                           @endif
                       </div>
                   </td>
                   <td style="border-style: none">{{ $member['user_email'] }}</td>
                   <td style="border-style: none">
                   <script type="text/javascript">
                           if(String({{$team_info['user_name']}}) != String({{$member['user_name']}})){
                               window.document.write("<button class='layui-btn  layui-btn-danger layui-btn-sm remove'>{{trans('Team/manageTeamMember.1')}}</button>");
                           }
                       </script>

                   </td>
               </tr>
           @endforeach
   </table>
<div style="margin-left: 100px">
   {{ $paged->links() }}
</div>
    </div>
    <script>

        $(function(){
            layui.use('layer',function(){
                $('.table-member').on('click', '.remove,.removeAll', function() {
                    //团队id
                    team_id = $('#team-id').val();
                    //用户列表
                    userId_list = new Array();
                    //触发事件按钮对应的用户id
                    user_id = $(this).parents().siblings('.user-id').val();
                    //判断是删除单个成员的事件
                    if(user_id!=null && user_id!=''){
                        userId_list.push(user_id);
                    }else{//判断是批量删除删除事件
                        $('.checkOne').each(function(){
                            if($(this).prop('checked')!=null && $(this).prop('checked')!=''){
                                users_id = $(this).parent().siblings('.user-id').val();
                                userId_list.push(users_id)
                            }
                        });
                    }
                    console.log('team_id:'+team_id);
                    console.log('userId_list:'+userId_list);
                    //用户名列表不为空则对后台发起请求
                    if(userId_list.length!=0){
                        $.ajax({
                            url: "{{url('team/removeMember')}}",
                            type: 'get',
                            dataType: 'json',
                            data: {'team_id':team_id,'userId_list': userId_list,"_token":"{{csrf_token()}}"},
                        })
                            .done(function(data) {
                                layer.msg(data.msg,{
                                    icon:data.icon,
                                    time:3000
                                });
                                if(data.icon=='1'){
                                    setTimeout('location.reload()',1000);
                                }
                            })
                            .fail(function() {
                                console.log("error");
                            })
                            .always(function() {
                                console.log("complete");
                            });
                    }else{
                        layer.msg('Please at least select one member！');
                    }
                });
            });
            //全选or全不选
            $('#checkAll').change(function(){
                $('.checkOne').prop('checked', $(this).prop('checked'))
            });
        });
    </script>
@endsection