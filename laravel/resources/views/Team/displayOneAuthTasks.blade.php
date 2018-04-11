{{--队长查看任务页面--}}
@extends('team.'.$AuthOrNot)

@section('team_content')
    <div class="layui-collapse box" lay-filter="test" style="height: auto;min-height: 630px" >
        @foreach($tasks as $task)
        <li class="layui-colla-item" style="width: 550px;">
            <h2 class="layui-colla-title" style="color: #0C0C0C;font-weight: 800;font-size: 15px;width: 530px;background-color: #34bf49">
                <span  style="color: #fcfcfc">Task Name : </span>
                {{ $task['task_name'] }}
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
            <div class="layui-colla-content" >
                <div style="min-height: 500px;height: auto;" >
                    <ul class="layui-timeline">
                        @foreach($task['trans'] as $tran)
                            <li class="layui-timeline-item" style="width: 540px;">
                                <i class="layui-icon layui-timeline-axis"></i>
                                <div class="layui-timeline-content layui-text" style="width: 500px;">
                                    <h3 class="layui-timeline-title">{{ date('Y-m-d H:i:s',$tran['time']) }}</h3>
                                    <p> <span style="color: #0C0C0C;font-size: 15px;font-weight: 800;">{{ $tran['trans_brief'] }}</span><br><br>

                                        <span style="color: #0C0C0C;font-size: 13px;">Detailed Information :</span>
                                        {{ $tran['trans_description'] }}
                                    </p>
                                    @if($tran['trans_Resource_intro']=='stask')
                                        <button type="submit" class="layui-btn choose" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fcfcfc;margin-bottom: 20px" id="sub_task_detail">
                                           Sub Task Detail
                                        </button>
                                        <input type="hidden" name="team_id" value="{{$team_info['team_id']}}">
                                        <input type="hidden" name="stask_id" value="{{$tran['trans_Resource_path']}}">
                                    @else
                                        @unless($tran['trans_Resource_path']==null)
                                        <span style="color: #0C0C0C;font-size: 13px;">Download Resource : </span>
                                        <a href={{URL::asset($tran['trans_Resource_path'])}} download={{$tran['trans_Resource_path']}}>
                                            <img src="{{URL::asset('/images/download.png')}}" >
                                        </a><br>
                                    @endunless
                                        @unless($tran['trans_Resource_intro']==null)
                                        <p>
                                            <span style="color: #0C0C0C;font-size: 13px;">Resource Description :</span>
                                            {{ $tran['trans_Resource_intro'] }}
                                        </p>
                                    @endunless
                                    @endif
                                    @unless($team_info['team_funder_id']!=session('user_id'))
                                    <p>
                                        <img src="{{URL::asset('/images/delete2.png')}}" class="delete_tran">
                                    </p>
                                    @endunless
                                    <input type="hidden" name="tran_id" value="{{$tran['tran_id']}}">
                                    <input type="hidden" name="originator" value="{{session('code')}}">
                                </div>
                            </li>
                        @endforeach
                        @unless($team_info['team_funder_id']!=session('user_id'))
                        <li class="layui-timeline-item" class="scroll" >
                            <i class="layui-icon layui-timeline-axis"></i>
                            <div class="layui-timeline-content layui-text" >
                                <h3 class="layui-timeline-title">
                                    <div class="layui-btn-group">
                                        <button class="layui-btn layui-btn-primary layui-btn-sm" id="add_tran"  onclick="isHidden('{{$task['task_id']}}')"><i class="layui-icon"></i></button>
                                        <form id="{{$task['task_id']}}" style="line-height:20px;margin: 20px;padding:10px;width: 500px;float:left;color: #0C0C0C;cursor: hand;display: none" class="form-group" enctype="multipart/form-data" method="post" action="{{ url('team/displayOneAuthTasks/'.$team_info['team_id']) }}">
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

                                            <div style="display: none" id="{{$task['task_id']+$task['task_id']}}">
                                                <div class="form-group{{ $errors->has('user_profile') ? ' has-error' : '' }}" style="">
                                                    <label for="trans_Resource_Path" class="col-md-4 control-label">Upload Resources</label>

                                                    <span class="col-md-6">
												<div class="aaa" >

													<span class="btn btn-success fileinput-button" style="background: #34bf49;border-color: #34bf49;border-radius: 0;margin-top: 10px;margin-bottom: 10px" >
														<span style="font-weight: 700;">Upload</span>
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

                                                <div class="form-group" style="margin-bottom: 20px;" >
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
                                            <input type="hidden" name="flag" value='1' id="flag">
                                            <a class="newA" onclick="isHidden('{{$task['task_id']+$task['task_id']}}')" style="color: #0C0C0C;float:left;display: block;width: 900px;margin-top: 30px" id="a">I Wanna Upload Resource. </a>
                                        </form>
                                    </div>
                                </h3>
                                <br>
                                <br><span style="color: #34bf49;font-weight: 800;">{{$task['timeLeft']}}</span></i>

                            </div>
                        </li>
                        @endunless
                    </ul>

                </div>
            </div>
        </li>
        @endforeach
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
                    event.preventDefault();
                    $(this).parent().parent().parent().remove();
                });
                $('.layui-timeline').on('click','#sub_task_detail',function(event){
                    event.preventDefault();
                    var stask_id = $(this).siblings("[name='stask_id']").val();
                    var stask_name = $(this).siblings("[name='stask_name']").val();
                    var team_id=$(this).siblings("[name='team_id']").val();
                    layer.open({
                        type: 2,
                        title: 'Sub Task Detail',
                        shadeClose: true,
                        shade: 0.8,
                        area: ['600px', '600px'],
                        content: 'displayOneAuthStask/'+team_id+'/?stask_id='+stask_id,
                        scrollbar:false
                    });
                });
            });
        });
    </script>
    {{ $paged->links() }}
@endsection