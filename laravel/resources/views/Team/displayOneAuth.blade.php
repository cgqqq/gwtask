{{--队长查看队员页面--}}
@extends('layouts.home')

@section('content')
	<div style="float: left;width: 800px;height: 750px;" class="team">
		<div style="float: left;width: 800px;height: 120px;border-bottom:4px #8D8D8D double;">
		<span style="font-size: 50px;font-weight: 800;color: #0C0C0C;font-family: Arial;margin-left: 40px;margin-top: 60px">
		{{ $team_info['team_name'] }}
			{{--<input type="hidden" name="team_id" value="{{ $team_info['team_id'] }}">
			<div style="float:right;margin-right: 20px;margin-top: 20px;background-color: transparent;width: 100px;height: 100px">
				@if(!$data['belong2team'])
					<button class="layui-btn layui-btn-small join-team" style="background-color: #fcfcfc;border: 1px solid #0C0C0C; color: #0C0C0C">Join</button>
				@else
					<button class="layui-btn layui-btn-small quit-team" style="background-color: #fcfcfc;border: 1px solid #0C0C0C; color: #0C0C0C" >Quit</button>
				@endif
		</div>--}}
		</span>

			<p STYLE="color: #8D8D8D;font-size: 15px;margin-left: 40px;margin-bottom: 6px" style="width: 500px	;float: left">
				Funder :
				<img class="activator" src="{{URL::asset('/uploads/user_profile/'.$team_info['user_profile'])}}"  style="width: 30px;height: 30px;border-radius: 100%" onclick='javascrtpt:window.location.href="{{ url('user/displayInfo') }}"'>

			</p>


		</div>
		<div style="float: left;width: 800px;height: 630px; ">
			<div style="border-right: double 4px #8D8D8D;float: left;width:250px;height: 630px; color: #0C0C0C">
				<div class="collection" style="margin-top: 20px;width: 230px;margin-right: 20px;padding: 5px;">

					<a href="#!" class="collection-item" style="font-size: 12px;line-height: 30px"><span class="new badge2" style="margin-top: 10px;font-size: 10px">99</span>
						All Tasks（所有）
					</a>
					<a href="#!" class="collection-item" style="font-size: 12px;line-height: 30px"><span class="new badge2" style="margin-top: 10px;font-size: 10px">99</span>
						Completed Tasks(已完成)
					</a>
					<a href="#!" class="collection-item" style="font-size: 12px;line-height: 30px"><span class="new badge2" style="margin-top: 10px;font-size: 10px">99</span>
						Uncompleted Tasks（未完成）
					</a>
					<a href="#!" class="collection-item" style="font-size: 12px;line-height: 30px"><span class="new badge2" style="margin-top: 10px;font-size: 10px">99</span>
						Resource Sharing
					</a>

				</div>
			</div>

			<div style="float: left;width: 550px;height: 630px; color: #0C0C0C">
				组员动态
			</div>

		</div>
	</div>
	<div style="float: left;width: 250px;height: 750px;">
		<div style="border-left: double 4px #8D8D8D;border-bottom:double 4px #8D8D8D;float: left;width: 250px;height: 200px;">
			<div class="layui-inline" style="margin-left:75px;margin-top: 30px" id="profile" >
				<img class="layui-circle" style="height: 100px;width:100px;" src="{{ asset(session('user_profile')) }}" onclick='javascrtpt:window.location.href="{{ url('user/displayInfo') }}"'>
				<p style="text-align: center;margin-top: 5px;color: #0C0C0C">
					{{session('user_name')}}
				</p>
			</div>
			<p style="margin-left: 30px" class="newA">
				<a href="{{  url('user/displayFollow')}}">{{$data['followNum']}} Followings </a> | <a href="{{  url('user/displayFollower')}}">{{$data['followerNum']}} Followers</a>

			</p>
		</div>

		<div style="float: left;width: 250px;height: 550px;border-left: double 4px #8D8D8D;color: #8d8d8d;">
			<div style="float: left;width: 250px;height: 250px;">
				<p style="margin-top: 10px;margin-left: 10px;font-size: 15px">
					Notice(群通告)</p>

			</div>
			<div style="float: left;width: 250px;height: 350px;">
				<div style="float: left;width: 200px;height: 20px">
					<p style="margin:10px;">
						Team Members:
					</p>
				</div>
				<div style="float: right;width:25px;height: 24px;margin-top: 5px;">
					<a href="{{url('team/manageTeamMember?team_name='.$team_info['team_name'])}}" >
						<img src="{{URL::asset('/images/7.png')}}"></a>
				</div>
				<div style="width: 245px;height: 520px;margin-left: 5px;margin-top: 24px;">
					@foreach($pageOut as $member)
						<div class="layui-inline" style="margin-left:30px;margin-top: 15px;width: 40px;float:left;" id="profile" >
                            @if($member['user_id']==session('user_id'))
							<img class="layui-circle" style="height: 50px;width:50px;" src="{{URL::asset('/uploads/user_profile/'.$member['user_profile'])}}" onclick='javascrtpt:window.location.href="{{url('user/displayInfo')}}"'>
							<p style="margin-top: 5px;color: #8D8D8D;font-weight: 500;font-size: 12px;text-align: center;margin-left: 5px;width:50px;height:20px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;  ">
								{{$member['user_name']}}
							</p>
                            @else
                                <img class="layui-circle" style="height: 50px;width:50px;" src="{{URL::asset('/uploads/user_profile/'.$member['user_profile'])}}" onclick='javascrtpt:window.location.href="{{url('user/displayOthersInfo/'.$member['user_id'])}}"'>
                                <p style="margin-top: 5px;color: #8D8D8D;font-weight: 500;font-size: 12px;text-align: center;margin-left: 5px;width:50px;height:20px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;  ">
                                    {{$member['user_name']}}
                                </p>
                            @endif
						</div>

					@endforeach
					{{--@if($data['finalPage'])
					<input type="hidden" name="team_id" value="{{ $team_info['team_id'] }}">
					<div class="layui-inline" style="margin-top: 20px;float: left;width: 40px;margin-left: 30px" id="profile" >
						<img id="add" class="layui-circle" style="height: 50px;width:50px;" src="{{URL::asset('/images/add.png')}}" onclick='javascrtpt:window.location.href="#"'>
						<p style="margin-top: 5px;color: #8D8D8D;font-weight: 500;font-size: 12px;text-align: center;margin-left: 5px">
							Add
						</p>
					</div>
					@else
					@endif--}}
					{{--	<div style="margin-top: 200px"></div>
                            {{ $paged->links() }}
                    </div>--}}

				</div>
			</div>
		</div>

		<script type="text/javascript">

            $(function(){
                layui.use('layer',function(){
                    $('.team').on('click','.join-team',function(){
                        var team_id = $(this).parent().siblings("[name='team_id']").val();
                        $.ajax({
                            url: "{{ url('team/join') }}",
                            type: 'get',
                            dataType: 'json',
                            data: {'team_id': team_id,'member_id':"{{ session('user_id') }}","_token":"{{csrf_token()}}"}
                        })
                            .done(function(data) {
                                layer.msg(data.msg,{
                                    icon:data.icon
                                });
                                if(data.icon==='1'){
                                    setTimeout("window.location.reload()",1000);

                                }
                            })
                            .fail(function() {
                                layer.msg('服务器未响应!',{
                                    icon:5
                                });
                            })
                            .always(function() {
                                console.log("complete");
                            });

                    });
                });
            });

            $(function(){
                layui.use('layer',function(){
                    $('.team').on('click','.quit-team',function(){
                        var team_id = $(this).parent().siblings("[name='team_id']").val();
                        $.ajax({
                            url: "{{ url('team/quit') }}",
                            type: 'get',
                            dataType: 'json',
                            data: {'team_id': team_id,"_token":"{{csrf_token()}}"}
                        })
                            .done(function(data) {
                                layer.msg(data.msg,{
                                    icon:data.icon
                                });
                                if(data.icon==='1'){
                                    setTimeout("window.location.reload()",1000);

                                }

                            })
                            .fail(function() {
                                layer.msg('服务器未响应!',{
                                    icon:5
                                });
                            })
                            .always(function() {
                                console.log("complete");
                            });

                    });
                });
            });




		</script>
		{{--

        @if($pageOut)

        <table class="layui-table" style="width: 900px;">
        <thead>
        <tr>
            <td>团队名称</td>
            <td>创始人</td>
            <td>团队介绍</td>
        </tr>
        </thead>
            <tr>
                <input type="hidden" id="team-id" value="{{ $team_info['team_id'] }}">
                <td id='team-name'>{{ $team_info['team_name'] }}</td>
                <td >{{ $team_info['user_name'] }}</td>
                <td>{{ $team_info['team_info'] }}</td>
            </tr>
        </table>
        <table class="layui-table table-member" style="width: 900px;">
            <thead>
                <tr>
                    <td >成员</td>
                    <td >邮箱</td>
                </tr>
            </thead>
            @foreach($pageOut as $member)
                <tr>
                    <input type="hidden" class="user-id" value="{{ $member['user_id'] }}">
                    <td class="user-name">
                        {{ $member['user_name'] }}
                    </td>
                    <td>{{ $member['user_email'] }}</td>
                </tr>
            @endforeach
        </table>
        {{ $paged->links() }}

        @else
            <div style="margin-top: 150px;margin-left: 450px">
                <span style="color: #8D8D8D;font-size: 20px;">NO RESULT</span>


            </div>

        @endif--}}

		<script type="text/javascript">
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
                            layer.msg('请选择组员！');
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