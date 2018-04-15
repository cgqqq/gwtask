{{--所有任务页面--}}
@extends('layouts.home')

@section('content')

<div class="layui-collapse box" lay-filter="test">
	@foreach($teams as $team)
		@unless(empty($team['tasks']))
		<span style="color: black;font-weight: 800;font-size: 20px;padding: 5px">{{trans('Task/displayAll.12')}}{{$team['team_name']}}</span>
		@endunless
		@foreach($team['tasks'] as $task)
	<li class="layui-colla-item" style="width: 1000px;" >
		<h2 class="layui-colla-title" style="color: #0C0C0C;font-weight: 800;font-size: 15px;width: 1030px;background-color: #34bf49">{{ $task['task_name'] }}
			<span  style="color: #fcfcfc">{{trans('Task/displayAll.1')}}</span> {{ $task['funder_name'] }}
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
		<div style="width:900px;height: 30px;margin-left: 30px">
			<div class="layui-progress layui-progress-big" lay-showpercent="true" >
				<div class="layui-progress-bar" lay-percent="{{$task['progress']}}%"></div>
			</div>
		</div>
		<div class="layui-colla-content scroll" id="tran_box">
			<div style="min-height: 500px;height: auto;">
				<ul class="layui-timeline">
					@foreach($task['trans'] as $tran)
					<li class="layui-timeline-item">
						<i class="layui-icon layui-timeline-axis"></i>
						<div class="layui-timeline-content layui-text">
							<h3 class="layui-timeline-title">{{ date('Y-m-d H:i:s',$tran['time']) }}</h3>
							<p> <span style="color: #0C0C0C;font-size: 15px;font-weight: 800;">
									@if($tran['trans_brief'] =='Task Created')
										{{trans('Task/displayAll.13')}}
									@elseif($tran['trans_brief']=='stask')
										{{trans('Task/displayAll.14')}}
									@else
										{{$tran['trans_brief']}}
									@endif
								</span><br><br>

								<span style="color: #0C0C0C;font-size: 13px;">{{trans('Task/displayAll.2')}}</span><br>
								{{ $tran['trans_description'] }}
							</p>
							@if($tran['trans_Resource_intro']=='stask')
								<button type="submit" class="layui-btn choose" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fcfcfc;margin-bottom: 20px" id="sub_task_detail">
									{{trans('Task/displayAll.3')}}
								</button>
								<input type="hidden" name="team_id" value="{{$team['team_id']}}">
								<input type="hidden" name="stask_id" value="{{$tran['trans_Resource_path']}}">
							@else
							@unless($tran['trans_Resource_path']==null)
								<span style="color: #0C0C0C;font-size: 13px;">{{trans('Task/displayAll.4')}}</span>
								<a href={{URL::asset($tran['trans_Resource_path'])}} download={{$tran['trans_Resource_path']}}>
									<img src="{{URL::asset('/images/download.png')}}" >
								</a><br>
							@endunless
							@unless($tran['trans_Resource_intro']==null)
								<p>
									<span style="color: #0C0C0C;font-size: 13px;">{{trans('Task/displayAll.5')}}</span>
									{{ $tran['trans_Resource_intro'] }}
								</p>
							@endunless
							@endif
							<p>
								<img src="{{URL::asset('/images/delete2.png')}}" class="delete_tran">
							</p>
							<input type="hidden" name="tran_id" value="{{$tran['tran_id']}}">
						</div>
					</li>
					@endforeach
					<li class="layui-timeline-item"  >
						<i class="layui-icon layui-timeline-axis"></i>
						<div class="layui-timeline-content layui-text" >
							<h3 class="layui-timeline-title">
								<div class="layui-btn-group">
									<button class="layui-btn layui-btn-primary layui-btn-sm" id="add_tran"  onclick="isHidden('{{$task['task_id']}}')"><i class="layui-icon"></i></button>
									<form id="{{$task['task_id']}}" style="line-height:20px;margin: 20px;padding:20px;width: 1000px;float:left;color: #0C0C0C;cursor: hand;display: none" class="form-group" enctype="multipart/form-data" method="post" action="{{ url('task/displayAll') }}">
										{{ csrf_field() }}
										<div class="form-group" style="margin-bottom: 40px;">
											<label class="col-md-4 control-label" >{{trans('Task/displayAll.6')}}</label>
											<div class="col-md-6">
												<input type="text" name='trans_brief' id="trans_brief" required="" class="form-control" >
											</div>
										</div>

										<div class="form-group" >
											<label class="col-md-4 control-label" >{{trans('Task/displayAll.7')}}</label>

											<div class="col-md-6">
												<textarea type="text" name="trans_description" id="trans_description" required="" class="form-control scroll" ></textarea>
											</div>
										</div>

										<div style="display: none" id="{{$task['task_id'].$task['task_id']}}">
											{{--<div class="form-group" style="margin-bottom: 10px;width: 680px;margin-top: 40px	">
											<label class="col-md-6 control-label"  style="margin-top: 10px" >Upload Resource</label>
											<button type="button" class="layui-btn" name="trans_Resource_Path" id="trans_Resource_Path" style="background-color: #34bf49;margin-top: 10px;">
												<i class="layui-icon">&#xe67c;</i>Upload Files
											</button>
										</div>--}}
										<div class="form-group{{ $errors->has('user_profile') ? ' has-error' : '' }}" style="">
											<label for="trans_Resource_Path" class="col-md-4 control-label">Upload Resources</label>

											<span class="col-md-6">
												<div class="aaa" >

													<span class="btn btn-success fileinput-button" style="background: #34bf49;border-color: #34bf49;border-radius: 0;margin-top: 10px;margin-bottom: 10px" >
														<span style="font-weight: 700;">{{trans('Task/displayAll.8')}}</span>
														<input type="file" id="trans_Resource_Path" class="" name="trans_Resource_Path" >
													</span>
												</div>

												@if ($errors->has('trans_Resource_Path'))
												<span class="help-block">
													<strong>{{ $errors->first('trans_Resource_Path') }}</strong>
												</span>
												@endif
											</span>
										</div>

										<div class="form-group" style="margin-bottom: 40px;" >
											<label class="col-md-4 control-label" >{{trans('Task/displayAll.9')}}</label>

											<div class="col-md-6">
												<textarea type="text" name="trans_Resource_intro" id="trans_Resource_intro" class="form-control" class="scroll"></textarea>
											</div>
										</div>
									</div>
									<div class="col-md-6 col-md-offset-4">
										<button id="submit" type="submit" class="layui-btn shadow submit" style="border: 2px solid #0C0C0C;color: #0C0C0C;margin-top: 20px">
											{{trans('Task/displayAll.10')}}
										</button>
									</div>
									<input type="hidden" name="task_id" value="{{$task['task_id']}}">
									<input type="hidden" name="flag" value='1'>
									<a class="newA" onclick="isHidden('{{$task['task_id'].$task['task_id']}}')" style="color: #0C0C0C;float:left;display: block;width: 900px;margin-top: 30px" id="a">{{trans('Task/displayAll.11')}} </a>
								</form>
							</div>
						</h3>
						<br>
						<br><span style="color: #34bf49;font-weight: 800;">{{$task['timeLeft']}}</span></i>

					</div>
				</li>
			</ul>

			</div>
		</div>
	</li>
@endforeach
	@endforeach

{{ $paged->links() }}
</div>

<script type="text/javascript">

	function isHidden(oDiv){
		var vDiv = document.getElementById(oDiv);
		vDiv.style.display = (vDiv.style.display == 'none')?'block':'none';
	}
	$(function(){
		layui.use('layer', function(){
			$('.box').on('click','.delete_tran',function(event){
				event.preventDefault();
				var id = $(this).parent().siblings("[name='tran_id']").val();
				$.ajax({
					url: "{{ url('task/deleteTransaction') }}",
					type: 'post',
					dataType: 'json',
					data: {"tran_id":id,"_token":"{{csrf_token()}}"}
				})
				.done(function(data) {
					layer.msg(data.msg);
				})
				.fail(function(data) {
					layer.msg(data.msg);
				})
				.always(function() {
					console.log("complete");
				});
                $(this).parent().parent().parent().remove();
			});

		});
        $('.layui-timeline').on('click','#sub_task_detail',function(event){
            event.preventDefault();
            var stask_id = $(this).siblings("[name='stask_id']").val();
            var team_id=$(this).siblings("[name='team_id']").val();
            layer.open({
                type: 2,
                title: 'Sub Task Detail',
                shadeClose: true,
                shade: 0.8,
                area: ['600px', '600px'],
                content:'displayOneAuthTasks/displayOneAuthStask/'+team_id+"/?stask_id="+stask_id,
                scrollbar:false
            });
        });
	});
</script>
@endsection