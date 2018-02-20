@extends('layouts.home')

@section('content')

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
@endsection