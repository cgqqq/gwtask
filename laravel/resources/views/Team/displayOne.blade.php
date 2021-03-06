{{--非队长查看队员页面--}}
@extends('layouts.home')


@section('content')


	{{----}}
	<div style="float: left;width: 800px;height: 750px;" class="team">
		<div style="float: left;width: 800px;height: 120px;border-bottom:4px #8D8D8D double;">
		<span style="font-size: 50px;font-weight: 800;color: #0C0C0C;font-family: Arial;margin-left: 40px;margin-top: 60px">
		{{ $team_info['team_name'] }}
			<input type="hidden" name="team_id" value="{{ $team_info['team_id'] }}">
            <input type="hidden" name="user_id" value="{{ session('user_id')}}">
			<div style="float:right;margin-right: 20px;margin-top: 20px;background-color: transparent;width: 100px;height: 100px">
				@if(!$data['belong2team'])
					<button class="layui-btn layui-btn-small join-team" style="background-color: #fcfcfc;border: 1px solid #0C0C0C; color: #0C0C0C">{{trans('Team/displayOne.1')}}</button>
				@else
					<button class="layui-btn layui-btn-small quit-team" style="background-color: #fcfcfc;border: 1px solid #0C0C0C; color: #0C0C0C" >{{trans('Team/displayOne.2')}}</button>
				@endif
		</div>
		</span>

			<p STYLE="color: #8D8D8D;font-size: 15px;margin-left: 40px;margin-bottom: 6px" style="width: 500px	;float: left">
                {{trans('Team/displayOne.3')}}
				<img class="activator" src="{{URL::asset('/uploads/user_profile/'.$team_info['user_profile'])}}"  style="width: 30px;height: 30px;border-radius: 100%" onclick='javascrtpt:window.location.href="{{url('user/displayOthersInfo/'.$team_info['team_funder_id'])}}"'>

			</p>


		</div>
		<div style="float: left;width: 800px;height: 630px; ">
			<div style="border-right: double 4px #8D8D8D;float: left;width:250px;height: 630px; color: #0C0C0C">
				<div class="collection" style="margin-top: 20px;width: 230px;margin-right: 20px;padding: 5px;">
                    <a href="{{url('team/displayOne',$team_info['team_name'])}}" class="collection-item" style="font-size: 12px;line-height: 30px">
                        {{trans('Team/displayOne.4')}}
                    </a>
                    <a href="{{url('team/displayOneAuthTasks',$team_info['team_id'])}}" class="collection-item" style="font-size: 12px;line-height: 30px">
                        {{trans('Team/displayOne.5')}}
                    </a>
					<a href="#!" class="collection-item" style="font-size: 12px;line-height: 30px">
                        {{trans('Team/displayOne.6')}}
					</a>
                    <a href="{{url('team/displayOneAuthResources',$team_info['team_id'])}}" class="collection-item" style="font-size: 12px;line-height: 30px">{{--<span class="new badge2" style="margin-top: 10px;font-size: 10px">99</span>--}}
                        {{trans('Team/displayOne.7')}}
                    </a>
				</div>
			</div>

            <div style="float: left;width: 550px;height: 630px; color: #0C0C0C" class="scroll">
                @section('team_content')
                    <div style="float: left;width: 540px;height: 38px;" id="profile">
                        <img src="{{URL::asset('/images/write.png')}}" width="20px" height="20px" style="float: right;margin-right: 30px;margin-top: 8px;"  onclick="isHidden('create_team_uplaoding')">
                    </div>
                    <ul class="collection" style="width: 510px;float: left">
                        <li id="create_team_uplaoding" class="collection-item shadow" style="width:510px;height:auto;min-height:180px;margin-left: 5px;margin-top: 10px;display: none;" >
                            <form id="upload_form" style="line-height:20px;margin: 5px;padding:10px;width: 500px;float:left;color: #0C0C0C;cursor: hand;" class="form-group">
                                <div class="form-group" >
                                    <div class="col-md-6" >
                                        <textarea type="text" id='upload_content' required="" class="form-control" style="max-width: 400px;width: 380px;height: 70px;max-height: 70px;"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-md-offset-4">
                                    <button id="submit" type="submit" class="layui-btn shadow submit" style="border: 2px solid #0C0C0C;color: #0C0C0C;margin-top: 10px">
                                        {{trans('Team/displayOne.8')}}
                                    </button>
                                </div>
                                <input type="hidden" value="{{$team_info['team_id']}}" name="team_id">
                            </form>
                        </li>
                        @foreach($uploadings as $uploading)
                            <li class="collection-item shadow" style="width:510px;height:auto;min-height:180px;margin-left: 5px;margin-top: 10px;"  >
                                <div style="height:100px;width: 130px;float: left;">
                                    @if(session('user_id')==$uploading['uploader_id'])
                                        <img src="{{URL::asset('/uploads/user_profile/'.$uploading['uploader_profile'])}}" class="layui-circle" width="65px" height="65px" style="margin-left: 15px;" onclick='javascrtpt:window.location.href="{{url('user/displayInfo')}}"' >
                                    @else
                                        <img src="{{URL::asset('/uploads/user_profile/'.$uploading['uploader_profile'])}}" class="layui-circle" width="65px" height="65px" style="margin-left: 15px;" onclick='javascrtpt:window.location.href="{{url('user/displayOthersInfo/'.$uploading['uploader_id'])}}"' >
                                    @endif

                                </div>
                                <div style="font-weight:600;font-size:18px;color:#0C0C0C;height:auto;min-height:130px;width: 340px;float: left;word-wrap:break-word;line-height: 30px;position:relative ;margin-top: 10px">
                                    <div style="width:20px;float: right;margin-left: 5px;" id="del"> <img src="{{URL::asset('/images/delete2.png')}}" class="delete_tran"></div>
                                    <input type="hidden" value="{{$uploading['uploading_id']}}" name="id">
                                    {{$uploading['content']}}

                                    @unless($uploading['resource']==null)
                                        <p style=" font-weight:600;font-size:15px;color:#0C0C0C;margin-bottom: 5px; ">
                                            {{trans('Team/displayOne.9')}}

                                            <a href={{URL::asset($uploading['resource'])}} download={{$uploading['resource']}}>
                                                <img src="{{URL::asset('/images/click.png')}}" >
                                            </a>
                                        </p>
                                    @endunless
                                    <p style="font-size: 10px;color:#8D8D8D;float:right;position:absolute;top:130px;padding: 0px;margin-right: 0px; ">
                                        {{ date('Y-m-d H:i:s',$uploading['time']) }}
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @show
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
				<a href="{{  url('user/displayFollow')}}">{{$data['followNum']}} {{trans('Team/displayOne.12')}} </a> | <a href="{{  url('user/displayFollower')}}">{{$data['followerNum']}} {{trans('Team/displayOne.13')}}</a>

			</p>
		</div>

		<div style="float: left;width: 250px;height: 550px;border-left: double 4px #8D8D8D;color: #8d8d8d;">
			<div style="float: left;width: 250px;height: 550px;">
				<p style="margin-top: 10px;margin-left: 10px;">
                    {{trans('Team/displayOne.10')}}</p>
				<div style="width: 245px;height: 520px;margin-left: 5px;margin-top: 8px;">
					@foreach($pageOut as $member)
						<div class="layui-inline" style="margin-left:30px;margin-top: 20px;width: 40px;float:left;" id="profile" >
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
				</div>
			</div>
		</div>
	</div>

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


        $(function() {
            layui.use('layer', function () {
                $('.team').on('click','.join-team',function(){
                    var team_id = $(this).parent().siblings("[name='team_id']").val();
                    var applicant_id = $(this).parent().siblings("[name='user_id']").val();;
                    $.ajax({
                        url: "{{ url('user/applyJoinTeam') }}",
                        type: 'post',
                        dataType: 'json',
                        data: {'team_id':team_id,'applicant_id':applicant_id},
                    })
                        .done(function(data) {
                            layer.msg(data.msg)});
                            /*if(data.icon==1||data.icon==2){
                                console.log('添加组员失败列表：'+data.fail_list)
                            }*/
                        })
                        .fail(function() {
                            console.log("error");
                        })
                        .always(function() {
                            console.log("complete");
                        });




            });
        });

        $(function(){
            layui.use('layer', function(){
                $('#upload_form').on('click','#submit',function(event){
                    event.preventDefault();
                    var id = $(this).parent().siblings("[name='team_id']").val();
                    var content=$('#upload_content').val();
                    $.ajax({
                        url: "{{ url('team/createTeamUploading') }}",
                        type: 'post',
                        dataType: 'json',
                        data: {'team_id':id,"content":content,"_token":"{{csrf_token()}}"}
                    })
                        .done(function(data) {
                            layer.msg(data.msg);
                            location.reload();

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
        function isHidden(oDiv){
            var vDiv = document.getElementById(oDiv);
            vDiv.style.display = (vDiv.style.display == 'none')?'block':'none';
        }
        $('.collection-item').on('click','#del',function(event){
            var id=$(this).siblings([name='id']).val();
            $.ajax({
                url: "{{url('team/deleteTeamUploading')}}",
                type: 'post',
                dataType: 'json',
                data: {'id':id,"_token":"{{csrf_token()}}"}
            })
                .done(function(data) {
                    layer.msg(data.msg);
                })
                .fail(function(data) {
                    layer.msg("Something went wrong,try again later");
                })
                .always(function() {
                    console.log("complete");
                });
            $(this).parent().parent().remove();
        });
	</script>

@endsection
