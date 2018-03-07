@extends('layouts.home') 


@section('content')

	<div class="layui-inline" style="margin-left:430px;" id="profile" >
		<img class="layui-circle" style="height: 150px;width:150px;" src="{{ asset(session('user_profile')) }}">

	</div>
	<div class="icons" style="float: none;padding-top:10px;margin-left: 408px;color: #0C0C0C">
		<ul>
			<li style="margin-right: 25px;"><a href="{{  url('user/displayFollow')  }}" class="following_icon"> </a></li>
			<li><a href="{{ url('user/displayFollower') }}" class="follower_icon"> </a></li>

		</ul>
	</div>
	<div class="personal_info">
	<hr class="layui-bg-black">
	User ID ：{{ $assign['user_id']}}
	<hr class="layui-bg-black">
	Name ：{{ $assign['user_name']}}
	<hr class="layui-bg-black">
	Email Address：{{ $assign['user_email']}}
	<hr class="layui-bg-black">
	<div id="password-field"></div>
	<button id="edit" class="layui-btn layui-btn-small">Change Passcode</button></div>
	<script type="text/javascript">
	$(function() {
		layui.use('layer',function(){
			$('#edit').on('click', function(event) {
				if($(this).text()==='Change Passcode'){
					$('#password-field').html("Passcode ：<input type='password' name='password' value={{ $assign['user_password']}}>");
					$('#edit').text('Confirm');
					return false;
				}else{
					event.preventDefault();
					var oldPass = "{{ $assign['user_password'] }}";
					var newPass = $("input[name='password']").val();
					if(newPass==='' || newPass===null){
						layer.tips('密码不能为空!','#password-field',{
							tips:1
						});
					}else if(oldPass===newPass){
						$('#password-field').html('');
						layer.msg('修改成功!',{
							icon:1
						});
					}else{
						$.ajax({
							url: "{{ url('user/editPass') }}",
							type: 'get',
							dataType:'json',
							data: {'user_id': "{{ session('user_id') }}",'user_password':newPass,"_token":"{{csrf_token()}}"},
						})
						.done(function(data) {
							if(data.flag==='1'){
								$('#password-field').html('');	
								layer.msg('修改成功!',{
									icon:1
								})
							}else{
								layer.msg('修改失败!',{
									icon:2
								});
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
						
					}
					$('#edit').text('Change Passcode');
				}
			
			});	
		});
		
	});
</script>
{{--<form action="" class='layui-form' >
	<table class='layui-table' style="width: 900px;" class="layui-table">
		<thead>
			<tr>
				<th>用户ID</th>
				<th>姓名</th>
				<th>邮箱</th>			
				<th>密码</th>
				<th>操作</th>
			</tr>
		</thead>
		<tr>
			<td>{{ $assign['user_id']}} </td>
			<td>{{ $assign['user_name']}} </td>
			<td>{{ $assign['user_email']}} </td>
			<td id="password-field"><input type="password" name="" value="{{ $assign['user_password']}}" readonly="" style="border: 0px;"> </td>
			<td>
				<button id="edit" class="layui-btn layui-btn-small">修改密码</button>
			</td>
		</table>
	</form>--}}

	@endsection