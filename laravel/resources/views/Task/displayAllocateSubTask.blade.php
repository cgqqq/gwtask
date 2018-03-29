{{--子任务增加及分配页面--}}
@extends('layouts.home')

@section('content')

<script type='text/javascript'>
	$(function(){
		layui.use('layer',function(){
			var team_user_list = [];
			if($('#has-get-users').val()=='0'){
				$.ajax({
					url: "{{url('task/getTeamUsers')}}",
					type: 'get',
					dataType: 'json',
					data: {'team_id':$('#team_id').val(),"_token":"{{csrf_token()}}"},
				})
				.done(function(data) {
					console.log("team_user_list:"+data);
					$('#has-get-users').val('1');
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				
			}
			//新增子任务
			$('#addSTask').on('click',function(){
				$('#STask_table').append("<tr class='sub-task-items'><td><input name='title' lay-verify='title' autocomplete='off' placeholder='请输入子任务名' class='layui-input  task_name' type='text'><input name='title' lay-verify='title' autocomplete='off' placeholder='请输入子任务描述' class='layui-input  task_descri' type='text'></td>' type='text'></td><td><button class='layui-btn layui-btn-primary layui-btn-sm del'><i class='layui-icon'></i></button></td></tr>");
			});
			//删除子任务
			$('#STask_table').on('click','.del',function(event){				
				$(this).parent().parent().remove();
				event.preventDefault();
			});
			//选择组员
			$('#STask_table').on('click','.choose-user',function(event){				
				$(this).parent().parent().remove();
				event.preventDefault();
			});
			//发布子任务
			$('#submit').on('click',function(){
				var sub_task_list = [];
				$('.sub-task-items').each(function(){
					var sub_task_item = [];
					var task_name = $(this).children().children('.task_name').val();
					var task_descri = $(this).children().children('.task_descri').val();
					sub_task_item.push(task_name);
					sub_task_item.push(task_descri);
					sub_task_list.push(sub_task_item);
				});
				console.log(sub_task_list);
			});
		});
	});
</script>
<input type="hidden" name="" id="team_id" value="{{$team_id}}">
<input type="hidden" name="" id="has-get-users" value="0">
<table class='layui-table' style='width: 900px;'>
	<thead>
		<tr>
			<td>所属团队</td>
			<td>任务名称</td>
		</tr>
	</thead>
	<input type='hidden' name='' value='{{$task_id}}'>
	<tr>			
		<td>{{$team_name}}</td>
		<td>{{$task_name}}</td>
	</tr>
</table>
<fieldset class='layui-elem-field site-demo-button' style='width: 900px'>  
	<legend>增加子任务</legend>
	<button class='layui-btn layui-btn-primary layui-btn-sm' id='addSTask'><i class='layui-icon'></i></button>
	<button class='layui-btn layui-btn-primary layui-btn-sm' id='submit'>发布</button>
	<form class='layui-form' action='' id="sub-task-form">
	<table class='layui-table' id='STask_table'>
			<tr class="sub-task-items">
				<td>
					<input name='title' lay-verify='title' autocomplete='off' placeholder='请输入子任务名' class='layui-input task_name' type='text'>
					<input name='title' lay-verify='title' autocomplete='off' placeholder='请输入子任务描述' class='layui-input task_descri' type='text'>
				</td>
				<td>
					<button class='layui-btn layui-btn-primary layui-btn-sm del'><i class='layui-icon'></i></button>
					<button class='layui-btn layui-btn-primary layui-btn-sm choose-user'><i class='layui-icon'>选择组员</i></button>
				</td>
			</tr>
		</table>
	</form>
</fieldset>


@endsection