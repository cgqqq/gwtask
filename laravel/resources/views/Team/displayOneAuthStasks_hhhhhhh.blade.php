@extends('team.'.$AuthOrNot)

@section('team_content')
<ul class="layui-collapse box" lay-filter="test" style="width: 650px">
    {{--each任务--}}
    @foreach($stasks as $stask)
<p style="color: black;font-weight: 800;font-size: 20px;">Task : {{$stask['task_name']}}</p>
        @foreach($stask['stasks'] as $aStask)
            {{--each子任务--}}
        <li class="layui-colla-item" style="width: 550px;background-color: #f8c1c3;height: auto;min-height:100px;">
            {{--title--}}
            <h2 class="layui-colla-title" style="color: #0C0C0C;font-weight: 800;font-size: 15px;width: 530px;background-color: #fff200;border: 2px solid black;">
                Sub Task Name : {{ $aStask['stask_name'] }}
                <span style="float: right;">
				@if( $aStask['status'] =='0')
                        <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/ongoing.png')}}" >未提交
                    @elseif($aStask['status']=='1')
                        <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/pending.png')}}" >审查中
                    @elseif($aStask['status']=='2')
                        <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/finishing.png')}}" >通过
                    @else
                        <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/unfinishing.png')}}" >未通过
                    @endif
			</span>
            </h2>
            <div class="layui-colla-content" style="background-color:#80deea;width: 550px;padding-bottom: 20px;">
                <div style="word-wrap:break-word;margin-left: 20px;margin-top: 20px">
                    <p style="word-wrap:break-word;font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #0C0C0C">Detail : </span>{{$aStask['stask_description']}}</p>
                    <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #0C0C0C">Responsible Teammate(s) : </span>
                    <div class="newA">
                        @foreach($aStask['members']  as $stask_member)
                            @if(session('user_id')==$stask_member['res_id'])
                                <img src="{{URL::asset('/uploads/user_profile/'.$stask_member['res_profile'])}}" width="35px" height="35px" style="margin-left: 10px;border-radius: 100%" >
                                {{$stask_member['res_name']}}
                            @else

                                <img src="{{URL::asset('/uploads/user_profile/'.$stask_member['res_profile'])}}" width="35px" height="35px" style="margin-left: 10px;border-radius: 100%" >
                                {{$stask_member['res_name']}}
                            @endif
                        @endforeach
                    </div>
                    </p>
                    <span style="display: none">{{$i='2'}}</span>
                    {{--获取该子任务的成员--}}
                    @foreach($aStask['members'] as $stask_member)
                        @unless($stask_member['res_id']!=session('user_id'))
                            <span style="display: none">{{$i='1'}}</span>
                        @endunless
                    @endforeach
                    {{--判断当前用户是否为该子任务的组员，1代表组员，2代表非组员--}}
                    @unless($i=='2')
                        {{--当前用户为该子任务的成员，判断该子任务状态，若=0，说明没有任何上传任何文件，form表格用于允许上传子任务--}}
                        @unless($aStask['status'] !='0')
                            <form class="form-group"  enctype="multipart/form-data" style="color: #333333;font-size: large;width: 90%;line-height:20px;float:left;margin-left:10px;color: #0C0C0C;cursor: hand;height: 200px;" id="submit_box" method="post" action="">
                                {{ csrf_field() }}
                                <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;width: 250px"><span style="font-size: 18px;color: #0C0C0C">Sub Task Submission :
                                                </span></p>
                                <div class="form-group" STYLE="width:150px;float: left ;margin-top: 20px" >
                                                    <span class="btn btn-success fileinput-button" style="background-color: #fcfcfc;float: left;border:4px solid black;width: 150px" >
                                                    <span style="font-weight: 700;color: #0C0C0C;font-size: 15px;">Select File</span>
                                                    <input type="file" id="file" class="" name="stask_file"  style="opacity: 0;">
                                                     </span>
                                </div>
                                <input type="hidden" name="stask_id" value="{{$aStask['stask_id']}}" >
                                <input type="hidden" name="flag" value="1" >
                                <button id="submit" type="submit" class="layui-btn shadow submit" style="font-size: 12px;border: 4px solid #0C0C0C;color: #0C0C0C;float: left;height: 40px;background-color: #fff200;margin-top: 20px;margin-left: 20px;width: 80px">
                                    Submit
                                </button>
                            </form>
                        @endunless
                    @endunless
                    {{--若已经上传过文件，允许更改已上传文件--}}
                    @unless($aStask['status'] =='0')
                        <form class="form-group"  enctype="multipart/form-data" style="color: #333333;font-size: large;width: 90%;line-height:20px;float:left;margin-left:10px;color: #0C0C0C;cursor: hand;height: 200px;display: none " id="{{$aStask['stask_id']}}submit_box" method="post" action="">

                            {{ csrf_field() }}
                            <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;width: 250px"><span style="font-size: 18px;color: #0C0C0C">
                                                </span></p>
                            <div class="form-group" STYLE="width:150px;float: left ;margin-top: 20px" >
                                                    <span class="btn btn-success fileinput-button" style="background-color: #fcfcfc;float: left;border:4px solid black;width: 150px" >
                                                    <span style="font-weight: 700;color: #0C0C0C;font-size: 15px;">Select File </span>
                                                    <input type="file" id="file" class="" name="stask_file"  style="opacity: 0;">
                                                     </span>
                            </div>
                            <input type="hidden" name="stask_id" value="{{$aStask['stask_id']}}" >
                            <input type="hidden" name="flag" value="1" >
                            <button id="submit" type="submit" class="layui-btn shadow submit" style="font-size: 12px;border: 4px solid #0C0C0C;color: #0C0C0C;float: left;height: 40px;background-color: #fff200;margin-top: 20px;margin-left: 20px;width: 80px">
                                Submit
                            </button>
                        </form>
                        <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%" ><span style="font-size: 18px;color: #0C0C0C">Submitted File :
                                            <a href={{URL::asset($aStask['infors'][0]['file'])}}  download={{$aStask['infors'][0]['file']}}><span>  </span>
                                            <img src="{{URL::asset('/images/download.png')}}" >
                                            </a></span>
                            <button id="submit" class="layui-btn submit" style="color: #0C0C0C;background-color: transparent"  onclick="isHidden('{{$aStask['stask_id']}}'+'submit_box')">
                                reload
                            </button>
                        @unless(empty($aStask['infors'][0]['score']))
                            <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%" ><span style="font-size: 18px;color: #0C0C0C">Score : </span>{{$aStask['infors'][0]['score']}}
                            </p>
                        @endunless
                        <div id="{{$aStask['stask_id']}}score" style="display: none">
                            <div id="action_score" style="float: left;width: 100%">
                                <form style="line-height:20px;float:left;color: #0C0C0C;margin-left: 10px;" class="form-group" id="{{$aStask['stask_id']}}">
                                    <div class="form-group" style="width: 250px;float: left">
                                        <div >
                                            <input type="text" required="" class="form-control" id="score_input" width="20px" style="border: 1px solid transparent">
                                        </div>
                                    </div>
                                    <div style="float: left;margin-left: 30px">
                                        <button id="submit_score" class="layui-btn shadow submit" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fcfcfc">
                                            Save
                                        </button>
                                        <input type="hidden" name="stask_id" value="{{$aStask['stask_id']}}" >
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endunless
                    {{--若已经已经提交子任务，并且当前用户为任务负责人，则允许进行评分以及评论--}}
                    @unless($aStask['status'] =='0'&&session('user_id')!=$stask['task_manager_id'])
                        <div class="icons" style="margin-top: 0;float: left;border-radius: 100%;margin-left: 30px" >
                            <ul>
                                <li><a class="comment_icon" onclick="isHidden('{{$aStask['stask_id']}}'+'comment')"> </a></li>
                                <li><a id="score" class="score_icon" onclick="isHidden('{{$aStask['stask_id']}}'+'score')"> </a></li>
                            </ul>
                        </div>
                    @endunless
                    {{--评论框--}}
                    <div id="{{$aStask['stask_id']}}comment" style="display: none">
                        <div id="action_comment" style="float: left;width: 100%">
                            <form style="line-height:20px;float:left;color: #0C0C0C;margin-left: 10px;" class="form-group" >
                                <div class="form-group" style="width: 300px;float: left;margin-left: 50px">
                                    <div >
                                        <textarea type="text" required="" class="form-control" ></textarea>
                                    </div>
                                </div>
                                <div style="float: left;margin-top: 20px;margin-left: 30px">
                                    <button id="submit_score" class="layui-btn shadow submit" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fcfcfc">
                                        send
                                    </button>
                                    <input type="hidden" name="stask_id" value="{{$aStask['stask_id']}}" >
                                </div>
                            </form>
                        </div>
                        <ul class="collection" style="float: left">
                            <li class="collection-item avatar" style="color: #0C0C0C;margin-bottom:10px;height: auto;min-height: 80px;display: table;width: 550px " >
                                <p style="font-size: 12px;margin-right: 0px;margin-top: 10px">
                                    {{--{{ date('Y-m-d H:i:s',$friendsUpdating['time']) }}--}}
                                </p>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>
        </li>
        @endforeach
    @endforeach
</ul>
    <script>
        function isHidden(oDiv){
            var vDiv = document.getElementById(oDiv);
            vDiv.style.display = (vDiv.style.display == 'none')?'block':'none';
        }
        $('.box').on('click','#submit_score',function(event){
            event.preventDefault();
            var stask_id = $(this).siblings("[name='stask_id']").val();
            var score= $(this).parent().parent().find('#score_input').val();
            $.ajax({
                url: "{{ url('task/score') }}",
                type: 'post',
                dataType: 'json',
                skin:'demo-class',
                data: {"stask_id":stask_id,'score':score,"_token":"{{csrf_token()}}"}
            })
                .done(function(data) {
                    layer.msg(data.msg);
                    window.location.reload();
                })
                .fail(function(data) {
                    layer.msg("Something went wrong,try again later");
                })
                .always(function() {
                    console.log("complete");
                });
            $(this).parent().parent().parent().style.display='none';
            layer.close(index);


        });

    </script>
@endsection