@extends('layouts.home')

@section('content')

<script type="text/javascript">
	$(function(){
		layui.use('layer',function(){
			$('.layui-table').on('click','.btn',function(){
				var team_name = $('.team_name').text();
				var user_list = [];
				$('.layui-form-checked').each(function(){
					
				});
			});
			//全选or全不选
			$('#checkAll').change(function(){
				$('.checkOne').prop('checked', $(this).prop('checked'))
			});
		});
	});
</script>

<table class="layui-table" style="width: 900px;">
<div style="color: black;" id="team_name">团队：{{ $team_name }}</div>
	<thead>
		<tr>
			<td>
				<input type="checkbox" id='checkAll' style="position: absolute;top: 12px;"><label for="checkAll" style="position: absolute;top: 15px;left: 35px;cursor: pointer;">用户ID</label>
			</td>
			<td>
				姓名
			</td>
			<td colspan="4">
				操作
			</td>
		</tr>
	</thead>
	@foreach($users as $user)
		<tr>
			<td class="td-id">
			<input type='checkbox' class='checkOne' style='display:inline-block;position :absolute ; top:12px;'>"
			<div style="position: absolute;top: 15px;left: 35px;display: inline-block;">{{ $user->user_id }}</div>
				
			</td>
			<td>
				{{ $user->user_name }}
			</td>
			<td>
				<button class="btn" >增加</button>
			</td>
			</td>
		</tr>
	@endforeach
</table>
{{ $users->links() }}
@endsection