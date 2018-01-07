@extends('layouts.home') 


@section('content')
<script type="text/javascript">
	$(function() {
		layui.use('layer',function(){
			$('#edit').on('click', function(event) {
				if($(this).text()==='修改密码'){
					$('#password-field').html("<input type='password' name='password' value={{ $assign['user_password']}}>");
					$('#edit').text('确认修改');
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
						$('#password-field').html('<input type="password" name="" value="{{ $assign['user_password']}}" readonly="" style="border: 0px;">');
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
								$('#password-field').html('<input type="password" name="" value='+data.newHash+' readonly="" style="border: 0px;">');
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
					$('#edit').text('修改密码');
				}
			
			});	
		});
		
	});
</script>
<form action="" class='layui-form' >
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
	</form>

	@endsection