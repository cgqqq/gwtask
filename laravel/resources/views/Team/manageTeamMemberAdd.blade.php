@extends('layouts.home')
{{--管理队员，发送邀请--}}
@section('content')
    <div style="width: 1050px;height: 40px;margin-bottom: 30px" id="profile"  class="newA" >

        <img class="layui-circle" style="border: 1px solid black;margin-left: 20px;margin-top: 10px" src="{{URL::asset('/images/back.png')}}"  href="javascript:void(0)"  onclick="javascript:window.history.go(-2);">

    </div>
    <div style="width: 1050px;height: 50px;color: black;margin-bottom: 30px">
        <p style="font-size: 50px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;text-decoration: none;margin-left: 50px;">Invite</p>
    </div>
    <div style="float: left;width: 120px;height:700px;margin-left: 30px;">
        <div style="margin-left: 20px;margin-top:50px;width: 40px;display: block" id='profile'>
            <img class="layui-circle" src="{{URL::asset('/images/delete.png')}}" onclick='javascrtpt:window.location.href="{{url('team/manageTeamMember?team_name='.$team_info['team_name'])}}"' >
            <img id="add" class="layui-circle" style="height: 50px;width:50px;margin-top: 60px;border: 2px solid black" src="{{URL::asset('/images/add.png')}}" onclick='javascrtpt:window.location.href="{{url('team/manageTeamMemberAdd?team_name='.$team_info['team_name'])}}"'>
        </div>
    </div>
    <div style="float: left;width: 870px;height: 800px;">
        <div class="search_input2" style="width: 870px;float: left;margin-top: 15px;margin-left: 250px">
            <form style="border-radius:40px;margin-right: 0;" method="post" action="{{url('team/manageTeamMemberAdd?team_name='.$team_info['team_name'])}}">
                {{ csrf_field() }}
                <input name='key' id="key" type="text" placeholder="Input User ID......" id="placeholder" style="border: 2px solid black">
            </form>
        </div>
        <div style="width: 870px;" class="result">
            @if(empty($result))
            @else

                <div class="layui-inline" style="margin-left:200px;margin-top: 30px;" id="profile" >
                    <img class="layui-circle" style="height: 100px;width:100px;" src="{{ asset('/uploads/user_profile/'.$result[0]['user_profile']) }}" onclick='javascrtpt:window.location.href="{{url('user/displayOthersInfo/'.$result['0']['user_id'])}}"'>
                    <p style="text-align: center;margin-top: 10px;color: #0C0C0C">
                       Name: {{$result[0]['user_name']}}
                    </p>

                </div>
                <div style="width:50px;float: right;margin-top: 70px;margin-right: 250px;color: #0C0C0C">
                    @if($result[0]['user_id']==session('user_id'))
                        <button class="layui-btn layui-btn-small" style="background-color: #34bf49;border: 1px solid #0C0C0C; color: #0C0C0C;">Me</button>
                    @elseif($isMember==1)
                        <button class="layui-btn layui-btn-small" style="background-color: #34bf49;border: 1px solid #0C0C0C; color: #0C0C0C">Member</button>
                    @else
                        <button class="layui-btn layui-btn-small invite" style="background-color: #fcfcfc;border: 1px solid #0C0C0C; color: #0C0C0C">Invite</button>
                        <input type="hidden" name="team_id" value="{{ $team_info['team_id'] }}">
                        <input type="hidden" name="user_id" value="{{ $result[0]['user_id'] }}">
                    @endif
                </div>

            @endif
        </div>
    </div>


    <script>
        $(function() {
            layui.use('layer', function () {
                $('.result').on('click','.invite',function(){
                    var team_id = $(this).siblings("[name='team_id']").val();
                    var user_id=$(this).siblings("[name='user_id']").val();
                    layer.prompt({title: 'Please Input Message Title!', closeBtn:2, btn:['Next','Cancle'],formType: 0}, function(text1,index){
                        layer.close(index);
                        layer.prompt({title: 'Invitation',btn:['Confirm','Cancle'],formType: 2}, function(text2,index){
                            var title=text1;
                            var content=text2;
                            $.ajax({
                                url: "{{ url('team/sendInvitation') }}",
                                type: 'post',
                                dataType: 'json',
                                data: {'team_id': team_id,'user_id':user_id,'title':title,'content':content,"_token":"{{csrf_token()}}"}
                            })
                                .done(function(data) {
                                    layer.msg(data.msg);
                                })
                                .fail(function() {
                                    layer.msg(data.msg);
                                })
                                .always(function() {
                                    console.log("complete");
                                });
                            layer.close(index);
                        });
                    });

                });
            });
        });
    </script>
@endsection