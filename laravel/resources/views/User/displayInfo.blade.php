@extends('layouts.home') 


@section('content')
<div style="width: 1030px;height: 800px">
	<div style="background-color: #4aaf51;width: 1030px;height: 300px;float: left;color: #0C0C0C" class="shadow">
		<img class="layui-circle" style="height: 150px;width:150px;margin-top: 5%;margin-left:43%" src="{{ asset(session('user_profile')) }}" >
		<p style=" margin-top: 10px;font-weight: 800;font-size: 16px;text-align:center; width:1030px;height:33px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;  ">
			{{ session('user_name')}}
		</p>
		<div style="background-color: #fcfcfc;width: 1030px;height: 45px;">
		<div class="icons" style="float: none;padding-top:10px;margin-left: 415px;color: #0C0C0C;">
			<ul>
				<li style="margin-right: 25px;"><a href="{{  url('user/displayFollow')  }}" class="following_icon"> </a></li>
				<li><a href="{{ url('user/displayFollower') }}" class="follower_icon"> </a></li>

			</ul>
		</div>
		</div>
		</div>
	<div style="width: 1030px;height: 500px;float: left">
		<div style="background-color: #0C0C0C;width: 350px;height: 500px;float: left">
			<div class="collection" style="margin-top: 20px;width: 350px;padding: 5px;">
				<a href="{{url('user/displayInfo')}}" class="collection-item" style="font-size: 20px;line-height: 30px;">
					<img class="layui-circle" style="height: 35px;width:35px;" src="{{URL::asset('/images/personal_info.png')}}" >
					{{trans('User/displayInfo.1')}}
				</a>
				<a href="{{url('user/displayInfoOptions')}}" class="collection-item" style="font-size: 20px;line-height: 30px">
					<img class="layui-circle" style="height: 35px;width:35px;" src="{{URL::asset('/images/option.png')}}" >
					{{trans('User/displayInfo.2')}}
				</a>
				@if($newsNum=='0')
				<a href="{{url('user/displayInfoMailBox')}}" class="collection-item" style="font-size: 20px;line-height: 30px">
					<img class="layui-circle" style="height: 35px;width:35px;" src="{{URL::asset('/images/mail_box.png')}}" >
					{{trans('User/displayInfo.3')}}
				</a>
					@else
					<a href="{{url('user/displayInfoMailBox')}}" class="collection-item" style="font-size: 20px;line-height: 30px"><span class="new badge2">{{$newsNum}}</span>
						<img class="layui-circle" style="height: 35px;width:35px;" src="{{URL::asset('/images/mail_box.png')}}" >
						{{trans('User/displayInfo.3')}}
					</a>
				@endif

			</div>
		</div>
		<div style="width: 680px;height: 500px;float: left">
			@section('displayInfo_content')
			<div class="personal_info">
				<hr class="layui-bg-black">
				{{trans('User/displayInfo.4')}}{{ session('user_id')}}
				<hr class="layui-bg-black">
				{{trans('User/displayInfo.5')}}{{ session('user_name')}}
				<hr class="layui-bg-black">
				{{trans('User/displayInfo.6')}}{{ session('user_email')}}
				<hr class="layui-bg-black">
				<button id="edit" class="layui-btn layui-btn-small" style="background-color: #fff200;border: 2px solid black;color: black">{{trans('User/displayInfo.7')}}</button>
				<div id="password-field" class="form-horizontal" style="display: none;" name="password-field">
					<div class="form-group">
						<label for="password-confirm" class="col-md-4 control-label">{{trans('User/displayInfo.40')}}</label>

						<div class="col-md-6">
							<input type='password' name='password1' id="user_password" value="{{ session('user_password')}}">
						</div>
					</div>
					<div class="form-group">
						<label for="password-confirm" class="col-md-4 control-label">{{trans('User/displayInfo.41')}}</label>

						<div class="col-md-6">
							<input type='password' name='password' id="password-confirm" value="{{ session('user_password')}}" required onblur="validate()">
						</div>
					</div>


				</div>
			</div>
				@show

		</div>
	</div>

</div>
	<script type="text/javascript">
	$(function() {
		layui.use('layer',function(){
			$('.personal_info').on('click',"#edit",function(event) {
                var vDiv = document.getElementById('password-field');
                vDiv.style.display = (vDiv.style.display == 'none')?'block':'none';
				if(vDiv.style.display=='block'){
					$('#edit').text('OK');
					return false;
				}
				else{
					event.preventDefault();
					var oldPass = "{{ session('user_password') }}";
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
					$('#edit').text('{{trans('User/displayInfo.7')}}');
				}
			
			});	
		});
		
	});
    function validate(){
        var pwd1 = document.getElementById("user_password").value;
        var pwd2 = document.getElementById("password-confirm").value;
        if(pwd1 != pwd2) {
            layui.use('layer', function(){
                layer.msg('The two passwords ' +
                    'you have entered are inconsistent !');
                document.getElementById("submit").disabled = true;
            });
        }
        else{
            document.getElementById("submit").disabled = false;
        }
    }
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