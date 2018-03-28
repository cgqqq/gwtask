{{--子任务增加及分配页面--}}
@extends('layouts.home')

@section('content')

<script type='text/javascript'>
	$(function(){
		layui.use('layer',function(){
			$('#addSTask').on('click',function(){
				$('#STask_table').append("<tr class='sub-task-items'><td><input name='title' lay-verify='title' autocomplete='off' placeholder='请输入子任务名' class='layui-input  task_name' type='text'><input name='title' lay-verify='title' autocomplete='off' placeholder='请输入子任务描述' class='layui-input  task_descri' type='text'></td>' type='text'></td><td><button class='layui-btn layui-btn-primary layui-btn-sm del'><i class='layui-icon'></i></button></td></tr>");
			});
			$('.sub-task-form').on('click','.del',function(){
				$(this).parent().remove();
			});
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
				</td>
			</tr>
		</table>
	</form>
</fieldset>


@endsection