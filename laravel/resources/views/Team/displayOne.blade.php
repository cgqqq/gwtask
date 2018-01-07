@extends('layouts.team')

@section('content')
<script type="text/javascript">
	$(function(){
		layui.use('layer',function(){
			$('.table-member').on('click', '.remove,.removeAll', function() {
				//团队名称
				team_name = $('#team-name').text()
				//用户列表
				name_list = new Array();
				//触发事件按钮对应的用户名
				user_name = $(this).parents().siblings('.user-name').text();
				//判断是删除单个成员的事件
				if(user_name!=null && user_name!=''){
					name_list.push(user_name);
				}else{//判断是批量删除事件
					$('.checkOne').each(function(){
						name = $(this).parent().text();
						if($(this).prop('checked')!=null && $(this).prop('checked')!=''){
							name_list.push(name)	
						}						
					});
				}
				console.log(name_list)
				$.ajax({
					url: "{{url('team/removeMember')}}",
					type: 'get',
					dataType: 'json',
					data: {'team_name':team_name,'user_list': name_list,"_token":"{{csrf_token()}}"},
				})
				.done(function() {
					console.log("success");
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				
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
			<td id='team-name'>{{ $team_info['team_name'] }}</td>
			<td >{{ $team_info['user_name'] }}</td>
			<td>{{ $team_info['team_info'] }}</td>
		</tr>		
</table>
<table class="layui-table table-member" style="width: 900px;">
		<thead>
			<tr>
				<td ><input type="checkbox" id='checkAll'>用户名</td>
				<td >邮箱</td>
				<td><button class="layui-btn layui-btn-danger layui-btn-sm removeAll">批量移除</button></td>
			</tr>
		</thead>
		@foreach($pageOut as $member)
			<tr>
				<td class="user-name"><input type="checkbox" class="checkOne">{{ $member['user_name'] }}</td>
				<td>{{ $member['user_email'] }}</td>
				<td><button class="layui-btn  layui-btn-sm remove">移除</button></td>
			</tr>
		@endforeach
</table>
{{ $paged->links() }}
@endsection