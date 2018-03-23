{{--子任务增加及分配页面--}}
@extends('layouts.home')

@section('content')

<script type="text/javascript">
	$(function(){
		layui.use('layer',function(){
			$('.layui-table').on('click','.join-team',function(){
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

<table class="layui-table" style="width: 900px;">
<thead>
	<tr>
		<td>任务名称</td>
		<td>任务描述</td>
		<td>所属团队</td>
		<td>发布人</td>
		<td>开始日期</td>
		<td>结束日期</td>
		<td>任务状态</td>
		<td>分配状态</td>
	</tr>
</thead>
		<tr>
			<td>{{$team_name}}</td>
		</tr>
</table>

@endsection