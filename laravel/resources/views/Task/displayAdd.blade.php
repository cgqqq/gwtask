{{--创建任务页面--}}
@extends('layouts.home')

@section('content')

<script type="text/javascript">
	$(function(){
		layui.use(['form', 'layedit', 'laydate'], function(){
			$('.form-horizontal').on('click','#submit',function(event){
				event.preventDefault();
				var team_id = "{{$team_id}}";
				var task_name = $('#task_name').val();
				var task_descri = $('#task_descri').val();
				var start_date = $('#start_date').val();
				var end_date = $('#end_date').val();
				var sDate = new Date(Date.parse(start_date));
				var eDate = new Date(Date.parse(end_date));
				// console.log('sDate:'+start_date+' eDate:'+end_date);
				// console.log('sDate:'+sDate+' eDate:'+eDate);
				console.log('team_id:'+team_id);
				if(task_name!=null&task_name!=''&&task_descri!=null&&task_descri!=''){
					if( start_date=='' || start_date==null || end_date=='' || end_date==null ){
						layer.msg('结束日期和开始日期不能为空！',{
							icon:7,
							time:4000
						});
					}else{
						if( eDate < sDate ){
						layer.msg('结束日期不能先于开始日期！',{
							icon:7,
							time:4000
						});
					}else{
						$.ajax({
							url: "{{ url('task/add') }}",
							type: 'post',
							dataType: 'json',
							data: {"team_id":team_id,"task_name": task_name,"task_descri":task_descri,"sDate":start_date,"eDate":end_date,"_token":"{{csrf_token()}}"}
						})
						.done(function(data) {
							layer.msg(data.msg,{
								icon:data.icon,
								time:2500
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
					}
					}
									
				}else{
					layer.msg('任务名和任务描述不能为空',{
						icon:7,
						time:2500
					});
				}
			});
			var form = layui.form
			,layer = layui.layer
			,layedit = layui.layedit
			,laydate = layui.laydate;
			//日期
			laydate.render({
                type: 'datetime',
			  	elem: '#start_date'
                /*,range: true*/
			});
			laydate.render({
                type: 'datetime',
				elem: '#end_date',
                /*range: true*/
			});
		});
	});
</script>
{{--用户创建的团队信息填写表格--}}
<p style="font-size: 50px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #333333;">Create a task</p>
<form class="form-horizontal"  style="color: #333333;font-size: large;margin-top: 100px;" >
	{{ csrf_field() }}
	
	<div class="form-group" style="margin-bottom: 40px;">
		<label for="task_name" class="col-md-4 control-label" >Task Name</label>

		<div class="col-md-6">
			<input type="text" id="task_name" required="" class="form-control" >
		</div>
	</div>
	<div class="form-group" style="margin-bottom: 40px;">
		<label for="task_description" class="col-md-4 control-label">Task Description</label>

		<div class="col-md-6"  id="a_input">
			<textarea rows="4" cols="50" class="form-control" id="task_descri"></textarea>

		</div>
	</div>
	<div class="form-group" style="margin-bottom: 40px;">
		<label for="task_description" class="col-md-4 control-label layui-form-label">开始日期</label>

		<div class="col-md-6"  id="a_input">
			<!-- <textarea rows="4" cols="50" class="form-control" id="task_description"></textarea> -->
			<input name="date" id="start_date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input form-control" type="text">
		</div>
	</div>
	<div class="form-group" style="margin-bottom: 40px;">
		<label for="task_description" class="col-md-4 control-label layui-form-label">结束日期</label>

		<div class="col-md-6"  id="a_input">
			<!-- <textarea rows="4" cols="50" class="form-control" id="task_description"></textarea> -->
			<input name="date" id="end_date" lay-verify="date" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input form-control" type="text">
		</div>
	</div>
	<div class="col-md-6 col-md-offset-4">
		<button id="submit" type="submit" class="layui-btn shadow" style="border: 2px solid #0C0C0C;color: #0C0C0C">
			CREATE
		</button>
	</div>
</form>

@endsection
