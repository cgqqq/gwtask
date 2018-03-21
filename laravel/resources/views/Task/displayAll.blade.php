{{--所有任务s页面--}}
@extends('layouts.home')

@section('content')
	<div class="layui-collapse" lay-filter="test">
		@foreach($tasks as $task)
		<li class="layui-colla-item" style="width: 1000px;">
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
						@foreach($task['trans'] as $tran)
						<li class="layui-timeline-item">
							<i class="layui-icon layui-timeline-axis"></i>
							<div class="layui-timeline-content layui-text">
								<h3 class="layui-timeline-title">{{ date('Y-m-d H:i:s',$tran['time']) }}</h3>
				<p> <span style="color: #0C0C0C;font-size: 15px;font-weight: 800;">{{ $tran['trans_brief'] }}</span><br><br>

					<span style="color: #0C0C0C;font-size: 13px;">Task Description :</span>
					{{ $tran['trans_description'] }}
				</p>
			</div>
		</li>
			@endforeach
			<li class="layui-timeline-item" class="scroll" >
				<i class="layui-icon layui-timeline-axis"></i>
				<div class="layui-timeline-content layui-text" >
					<h3 class="layui-timeline-title">
						<div class="layui-btn-group">
							<button class="layui-btn layui-btn-primary layui-btn-sm" id="add_tran"  onclick="isHidden('create_trans_form')"><i class="layui-icon"></i></button>
							<form id="create_trans_form" style="line-height:20px;margin: 20px;padding:20px;width: 1000px;float:left;color: #0C0C0C;cursor: hand;display: none" class="form-group">
								{{ csrf_field() }}
								<div class="form-group" style="margin-bottom: 40px;">
									<label class="col-md-4 control-label" >Transaction Title</label>

									<div class="col-md-6">
										<input type="text" name='trans_brief' id="trans_brief" required="" class="form-control" >
									</div>
								</div>

								<div class="form-group" >
									<label class="col-md-4 control-label" >Transaction Description</label>

									<div class="col-md-6">
										<textarea type="text" name="trans_description" id="trans_description" required="" class="form-control scroll" ></textarea>
									</div>
								</div>

								<div style="display: none" id="upload">
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
            <span style="font-weight: 700;">Upload</span>
            <input id="trans_Resource_Path" type="file" class="" name="trans_Resource_Path" required >
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
									<label class="col-md-4 control-label" >Resource Description</label>

									<div class="col-md-6">
										<textarea type="text" name="trans_Resource_intro" id="trans_Resource_intro" class="form-control" class="scroll"></textarea>
									</div>
								</div>
								</div>
								<div class="col-md-6 col-md-offset-4">
									<button id="submit" type="submit" class="layui-btn shadow submit" style="border: 2px solid #0C0C0C;color: #0C0C0C;margin-top: 20px">
										Submit
									</button>
								</div>
								<input type="hidden" name="task_id" value="{{$task['task_id']}}">
								<a class="newA" onclick="isHidden('upload')" style="color: #0C0C0C;float:left;display: block;width: 900px;margin-top: 30px" id="a">I Wanna Upload Resource. </a>
							</form>
						</div>
					</h3>
					<br>
					<br><span style="color: #34bf49;font-weight: 800;">{{$task['timeLeft']}}</span></i>

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

    function isHidden(oDiv){
        var vDiv = document.getElementById(oDiv);
        vDiv.style.display = (vDiv.style.display == 'none')?'block':'none';
    }

    $(function(){
        layui.use('layer', function(){
            $('#create_trans_form').on('click','#submit',function(event){
                event.preventDefault();
                var task_id = $(this).parent().siblings("[name='task_id']").val();
                var trans_brief=$('#trans_brief').val();
                var trans_description=$('#trans_description').val();
                var trans_Resource_intro=$('#trans_Resource_intro').val();
                if(trans_Resource_intro==''){
                    trans_Resource_intro=null;
				}
                $.ajax({
                    url: "{{ url('task/createTransaction') }}",
                    type: 'post',
                    dataType: 'json',
                    data: {"task_id":task_id,"trans_brief": trans_brief,"trans_description":trans_description,'trans_Resource_intro':trans_Resource_intro,"_token":"{{csrf_token()}}"}
                })
                    .success(function(data) {
                        layer.msg(data);

                    })
                    .fail(function(data) {
                        layer.msg(data);
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