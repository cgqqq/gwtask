@extends('layouts.team')
@section('content')

<script type="text/javascript">
	$(function(){
		layui.use('layer',function(){
			$('.layui-table').on('click','.quit-team',function(){
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
						setTimeout("displayMine()",1500);
						
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
	function displayMine(){
		location.href="{{ url('team/displayMine') }}";
	}
</script>

<table class="layui-table table-teamInfo" style="width: 900px;">
<thead>
	<tr>
		<td>团队名称</td>
		<td>创建者ID</td>
		<td>创建日期</td>
		<td>团队介绍</td>
		<td>操作</td>
	</tr>
</thead>
	@foreach($pageout as $team)
	<tr>
		<input type="hidden" name="team_id" value="{{ $team['team_id'] }}">
		<td><a href="{{ route('displayOneTeam',['team_name'=>$team['team_name']]) }}">{{ $team['team_name'] }}</a></td>
		<td>{{ $team['team_funder_id'] }}</td>
		<td>{{ date('Y-m-d H:i:s',$team['created_at']) }}</td>
		<td>{{ $team['team_info'] }}</td>
		<td><button class="quit-team">退出</button></td>
	</tr>
	@endforeach
</table>
{{ $paged->links() }}
@endsection