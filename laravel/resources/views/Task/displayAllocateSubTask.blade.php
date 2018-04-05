{{--子任务增加及分配页面--}}
@extends('layouts.home')

@section('content')
<script type="text/javascript" src="{{ asset('js/json2.js') }}"></script>
<script type='text/javascript'>
	$(function(){
		layui.use('layer',function(){
			var $ = layui.jquery, layer = layui.layer;
			//初始化团队成员列表变量
			var team_user_list = null ;
			//初始化加载团队成员列表
			if($('#has-get-users').val()=='0'){
				$.ajax({
					url: "{{url('task/getTeamUsers')}}",
					type: 'get',
					dataType: 'json',
					data: {'team_id':$('#team_id').val(),"_token":"{{csrf_token()}}"},
				})
				.done(function(data) {
					//将后台返回的该团队所有成员json对象转json字符串
					tmp = JSON.stringify(data.team_user_list);
					console.log(tmp);
					//json字符串转数组
					team_user_list = JSON.parse(tmp);
					for(var i in team_user_list){
						//为每一个组员初始化变量，判断是否被选
						team_user_list[i].push({'isSelected':'0'});
						console.log(i+':'+team_user_list[i][1].user_name+','+team_user_list[i][0].user_id+','+team_user_list[i][2].isSelected);

					}
					// console.log(team_user_list[1][1].user_name);
					console.log(team_user_list);
					
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
				var sub_task_html = 
				'<tr class="sub-task-items">'+
				'<td>'+
				'<input name="title" lay-verify="title" autocomplete="off" placeholder="请输入子任务名" class="layui-input task_name" type="text">'+
				'<input name="title" lay-verify="title" autocomplete="off" placeholder="请输入子任务描述" class="layui-input task_descri" type="text">'+
				'</td>'+
				'<td>'+
				'<button class="layui-btn layui-btn-primary layui-btn-sm del"><i class="layui-icon"></i></button>'+
				'<button data-type="auto" class="layui-btn layui-btn-normal choose">选择组员</button>'+
				'<b></b>'+
				'</td>'+
				'</tr>';
				$('#STask_table').append(sub_task_html);
			});
			//删除子任务
			$('#STask_table').on('click','.del',function(event){	
				event.preventDefault();
				$(this).parent().parent().remove();
			});
			//选择组员
			$('#STask_table').on('click','.choose',function(event){
				//要显示选中组员的地方
				var parent_user_box = $(this).next();
				event.preventDefault();
				allocate_user = [];
				layer.open({
						type: 1,
						skin: 'layui-layer-rim', //加上边框
						area: ['200px', '240px'], //宽高
						content:'<table class="layui-table team_user_item" ><tr><td><label class="layui-form-label" style="padding:0;">用户列表</label></td><td><button class="layui-btn layui-btn-primary layui-btn-xs confirm">确认</button></td></tr></table>'	
				});	
				var user_id_head = "<input type='hidden' name='' class='user_id' value='";
				var user_id_tail = "'>";
				var checkBox = "<input name='' lay-skin='primary' type='checkbox' style='position: relative;top:5px;margin-right:5px;''>";
				//显示所有可选组员
				for(var i in team_user_list){
						// console.log(team_user_list[i]);
						if(team_user_list[i][2].isSelected!='1'){
							$('.team_user_item').append("<tr><td style='position:relative;left:5px;' colspan='2'>"+user_id_head+team_user_list[i][0].user_id+user_id_tail+checkBox+"<b class='user_name_item'>"+team_user_list[i][1].user_name+"</b>"+"</td></tr>");
						}
				}
				//确认选中的队员			
				$('.confirm').on('click',{'user_box':parent_user_box},function(event){
					//保存传过来的要显示选中组员的地方
					var user_box = event.data.user_box;
					//保存选中的组员列表
					$('input:checked').each(function(){
						selected_user = $(this).next().html();
						allocate_user.push(selected_user);
						for(var i in team_user_list){
							if(selected_user.trim()==team_user_list[i][1].user_name){
								team_user_list[i][2] = {'isSelected':'1'};
							}
						}
					});
					// console.log('选中列表:'+allocate_user);
					console.log('选完用户后所剩用户：'+JSON.stringify(team_user_list));
					layer.closeAll();		
					for(var i in allocate_user){
						// alert(allocate_user[i]);
						user_box.append(allocate_user[i]+',');
					}					
				});
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
	function objToArray(array) {
    var arr = []
    for (var i in array) {
        arr.push(array[i]); 
    }
    console.log(arr);
    return arr;
}
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
					  <button data-type="auto" class="layui-btn layui-btn-normal choose">选择组员</button>
					  <b></b>
				</td>
			</tr>
		</table>
	</form>
</fieldset>

<tr class="sub-task-items">
<div></div>				
@endsection
