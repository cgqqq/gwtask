{{--所有任务s页面--}}
@extends('layouts.home')

@section('content')
	<div class="layui-collapse" lay-filter="test">
		@foreach($tasks as $task)
		<div class="layui-colla-item" style="width: 1000px;">
			<h2 class="layui-colla-title" style="color: #0C0C0C;font-weight: 800;font-size: 15px;width: 1030px;background-color: #34bf49">{{ $task['task_name'] }}
				<span  style="color: #fcfcfc">Published By : </span> {{ $task['1'] }} <span  style="color: #fcfcfc"> From </span> {{$task['0']}}
				<span style="float: right;">
				@if( $task['task_status'] =='0')
						<img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/pending.png')}}" >
					@elseif($task['task_status']=='1')
						<img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/ongoing.png')}}" >
					@else
						<img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/finishing.png')}}" >
					@endif
			</span>

			</h2>

			<div class="layui-colla-content">
				<p style="max-height: 500px;height: auto;" class="scroll">
					<ul class="layui-timeline">
						<li class="layui-timeline-item">
							<i class="layui-icon layui-timeline-axis"></i>
							<div class="layui-timeline-content layui-text">
								<h3 class="layui-timeline-title">{{ date('Y-m-d H:i:s',$task['task_kickoff_date']) }}</h3>
				<p> <span style="color: #0C0C0C;font-size: 15px;font-weight: 800;">Created Task</span><br>
					<span style="color: #0C0C0C;font-size: 13px;">Task Description :</span><br>
					{{ $task['task_description'] }}
					<br>
					<br>XX hours XX mins XX seconds left</i>
				</p>
			</div>
			</li>
			<li class="layui-timeline-item">
				<i class="layui-icon layui-timeline-axis"></i>
				<div class="layui-timeline-content layui-text">
					<h3 class="layui-timeline-title">+</h3>

				</div>
			</li>
			</ul>

				</p>
			</div>

		</div>
	<div class="layui-colla-item" style="width: 1000px;line-height:50px;">

			<img class="layui-circle" style="height: 30px;width:30px;margin-left: 500px;" src="{{URL::asset('/images/add.png')}}" onclick='javascrtpt:window.location.href="#"	' >

	</div>
			@endforeach
	</div>
<script type="text/javascript">
	$(function(){
		layui.use('layer',function(){
			$('.layui-table').on('click','.join-team',function(){
				var team_id = $(this).parent().siblings("[name='team_id']").val();
				$.ajax({
					url: "{{ url('team/join') }}",
					type: 'get',
					dataType: 'json',
					data: {'team_id': team_id,'member_id':"{{ session('user_id') }}","_token":"{{csrf_token()}}"}
				})
				.done(function(data) {
					layer.msg(data.msg,{
						icon:data.icon
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
				
			});
		});
	});
</script>
{{--

<table class="layui-table" style="width: 900px;">
<thead>
	<tr>
		<td>任务名称</td>
		<td>任务描述</td>
		<td>所属团队</td>
		<td>发布人</td>
		<td>开始日期</td>
		<td>结束日期</td>
		<td>任务状态</td>
	</tr>
</thead>
	@foreach($tasks as $task)
		<tr>
			<td id="team_name"><a href="" style="color: black;">{{ $task['task_name'] }}</a></td>
			<td>{{ $task['task_description'] }}</td>
			<td>{{ $task['0'] }}</td>
			<td>{{ $task['1'] }}</td>
			<td>{{ date('Y-m-d H:i:s',$task['task_kickoff_date']) }}</td>
			<td>{{ date('Y-m-d H:i:s',$task['task_deadline']) }}</td>
			<td><script type="text/javascript">
				@if( $task['task_status'] =='0'){
					document.write("未开始");
				}
				@elseif($task['task_status']=='1')
					document.write("进行中");
				@else{
					document.write("已结束");
				}
				@endif
			</script>
			
			</td>
		</tr>
		@endforeach
</table>
{{ $paged->links() }}
--}}

@endsection