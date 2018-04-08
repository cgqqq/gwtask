{{--创建团队页面--}}
@extends('layouts.home')

@section('content')

<script type="text/javascript">
	$(function(){
		layui.use('layer',function(){
			$('.form-horizontal').on('click','#submit',function(event){
				event.preventDefault();
				var team_name = $('#team_name').val();
				var team_info = $('#team_info').val();
				if(team_name!=null&team_name!=''&&team_info!=null&&team_info!=''){
					$.ajax({
						url: "{{ url('team/add') }}",
						type: 'post',
						dataType: 'json',
						data: {"team_name": team_name,"team_info":team_info,"_token":"{{csrf_token()}}"}
					})
					.done(function(data) {
						layer.msg(data.msg,{
							icon:data.icon,
							time:2500
						});
                        window.location.href="{{url('team/displayMineCre/default')}}";

	                })
					.fail(function() {
						layer.msg('服务器未响应!',{
							icon:5
						});
					})
					.always(function() {
						console.log("complete");
					});
				}else{
					layer.msg('团队名和团结介绍不能为空',{
							icon:7,
							time:2500
						});
				}
			});
		});
	});
</script>
{{--用户创建的团队信息填写表格--}}
			<p style="font-size: 50px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #333333;">Create My Team</p>
			<form class="form-horizontal"  style="color: #333333;font-size: large;margin-top: 100px;" >
				{{ csrf_field() }}
				<div class="form-group" style="margin-bottom: 40px;">
						<label for="team_name" class="col-md-4 control-label" >Team Name</label>

						<div class="col-md-6">
							<input type="text" id="team_name" required="" class="form-control" >
						</div>
				</div>
				<div class="form-group" style="margin-bottom: 40px;">
					<label for="team_info" class="col-md-4 control-label">Brief Introduction</label>

					<div class="col-md-6"  id="a_input">
						<textarea rows="4" cols="50" class="form-control" id="team_info"></textarea>

					</div>
				</div>
				<div class="col-md-6 col-md-offset-4">
					<button id="submit" type="submit" class="layui-btn shadow" style="border: 2px solid #0C0C0C;color: #0C0C0C">
						CREATE
					</button>
				</div>
			</form>

@endsection
