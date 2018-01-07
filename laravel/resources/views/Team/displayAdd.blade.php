@extends('layouts.team')

@section('content')

<script type="text/javascript">
	$(function(){
		layui.use('layer',function(){
			$('.layui-table').on('click','#submit',function(event){
				event.preventDefault();
				var team_name = $('#team_name').val();
				var team_info = $('#team_info').val();
				$.ajax({
					url: "{{ url('team/add') }}",
					type: 'post',
					dataType: 'json',
					data: {"team_name": team_name,"team_info":team_info,"_token":"{{csrf_token()}}"}
				})
				.done(function(data) {
					layer.msg(data.msg,{
						icon:data.icon
					});
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

<form class="layui-form" >
	<table class="layui-table" style="width: 900px;">
		<thead>
			<tr>
				<td>
					团队名称
				</td>
				<td>
					团队介绍
				</td>
				<td>
					操作
				</td>
			</tr>
		</thead>
		<tr>
			<td>
				<input type="text" id="team_name" required="">
			</td>
			<td>
				<input type="text" id="team_info" required="">
			</td>
			<td>
				<button id="submit" class="layui-btn layui-btn-small layui-btn-normal">提交</button>
			</td>
		</tr>
	</table>
</form>

@endsection
