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
		<td>团队名称</td>
			<td>创建者ID</td>
			<td>创建日期</td>
			<td>团队简介</td>
			<td>操作</td>
	</tr>
</thead>
	@foreach($teams as $team)
		<tr>
			<input type="hidden" name="team_id" value="{{ $team->team_id }}">
			<td id="team_name"><a href="{{url('team/displayOne',['team_name'=>$team->team_name])}}" style="color: black;">{{ $team->team_name }}</a></td>
			<td>{{ $team->team_funder_id }}</td>
			<td>{{ date('Y-m-d H:i:s',$team->created_at) }}</td>
			<td>{{ $team->team_info }}</td>
			<td><button class="layui-btn layui-btn-small join-team">加入</button></td>
		</tr>
		@endforeach
</table>
{{ $teams->links() }}

@endsection