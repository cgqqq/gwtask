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
				'<tr class="sub_task_item">'+
				'<td>'+
				'<input name="title" lay-verify="title" autocomplete="off" placeholder="请输入子任务名" class="layui-input sub_task_name" type="text">'+
				'<input name="title" lay-verify="title" autocomplete="off" placeholder="请输入子任务描述" class="layui-input sub_task_descri" type="text">'+
				'</td>'+
				'<td>'+
				'<button class="layui-btn layui-btn-primary layui-btn-sm del"><i class="layui-icon"></i></button>'+
				'<button data-type="auto" class="layui-btn layui-btn-normal choose">选择组员</button>'+
				'<b class="user_box"></b>'+
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
				$('.confirm').on('click',{'parent_user_box':parent_user_box},function(event){
					//传过来的子任务界面要显示选中组员的地方
					var parent_user_box = event.data.parent_user_box;
					//选中的组员列表
					$('input:checked').each(function(){
						var selected_user_id = $(this).prev().val();
						var selected_user = $(this).next().html();
						//所选中组员的user_id和user_name
						var selected_user_info = {
							'selected_user_id':selected_user_id,
							'selected_user':selected_user
						};
						allocate_user.push(selected_user_info);
						for(var i in team_user_list){
							//若未被选中组员与当前
							if(selected_user.trim()==team_user_list[i][1].user_name){
								team_user_list[i][2] = {'isSelected':'1'};
							}
						}
					});
					console.log('选中列表:'+JSON.stringify(allocate_user));
					console.log('选完用户后：'+JSON.stringify(team_user_list));
					//关闭选择子任务成员界面
					layer.closeAll();		
					//对指定子任务显示显示所选组员姓名及传user_id
					for(var i in allocate_user){
						// alert(allocate_user[i]);
						parent_user_box.append('<input type="hidden" name="" class="selected_user_id" value="'+allocate_user[i].selected_user_id+'">');
						parent_user_box.append(allocate_user[i].selected_user+',');
					}					
				});
			});
			//发布子任务
			$('#submit').on('click',function(){
				var sub_task_all = [];
				$('.sub_task_item').each(function(){
					//子任务项
					var sub_task_item = [];
					//子任务成员user_id
					var sub_task_users = [];
					//子任务名
					var sub_task_name = $(this).find('.sub_task_name').val();
					//子任务描述
					var sub_task_descri = $(this).find('.sub_task_descri').val();
					$(this).find('.user_box').find('.selected_user_id').each(function(){
						sub_task_users.push($(this).val());
					});
					// sub_task_item['sub_task_name'] = sub_task_name;
					// sub_task_item['sub_task_descri'] = sub_task_descri;
					// sub_task_item['sub_task_users'] = sub_task_users;

					sub_task_item = {
						'sub_task_name':sub_task_name,
						'sub_task_descri':sub_task_descri,
						'sub_task_users':sub_task_users
					};
					sub_task_all.push(sub_task_item);
				});
				console.log('传给后台的子任务信息及对应成员:'+sub_task_all);
				$.ajax({
					url: "{{ url('task/allocateSub') }}",
					type: 'post',
					dataType: 'json',
					data: {'task_id':$('#task_id').val(),'sub_task_all':sub_task_all,"_token":"{{csrf_token()}}"},
				})
				.done(function(data) {
						layer.msg(data.msg);
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				
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
{{ csrf_field() }}
<input type="hidden" name="" id="team_id" value="{{$team_id}}">
<input type="hidden" name="" id="has-get-users" value="0">
<table class='layui-table' style='width: 900px;'>
	<thead>
		<tr>
			<td>团队名称</td>
			<td>任务名称</td>
		</tr>
	</thead>
	<input type='hidden' name='' id='task_id' value='{{$task_id}}'>
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
			<tr class="sub_task_item">
				<td>
					<input name='title' lay-verify='title' autocomplete='off' placeholder='请输入子任务名' class='layui-input sub_task_name' type='text'>
					<input name='title' lay-verify='title' autocomplete='off' placeholder='请输入子任务描述' class='layui-input sub_task_descri' type='text'>
				</td>
				<td>
					<button class='layui-btn layui-btn-primary layui-btn-sm del'><i class='layui-icon'></i></button>
					  <button data-type="auto" class="layui-btn layui-btn-normal choose">选择组员</button>
					  <b class="user_box"></b>
				</td>
			</tr>
		</table>
	</form>
</fieldset>

<tr class="sub-task-items">
@endsection
