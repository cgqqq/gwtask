@extends('team.'.$AuthOrNot)

@section('team_content')
<ul class="layui-collapse box" lay-filter="test" style="width: 650px">
  {{--  {{dump($stasks)}}--}}
    @foreach($stasks as $stask)
<p style="color: black;font-weight: 800;font-size: 20px;">{{trans('Team/dOAStasks.1')}}{{$stask['task_name']}}</p>
        @foreach($stask['stasks'] as $aStask)
        <li class="layui-colla-item" style="width: 550px;">
            <h2 class="layui-colla-title" style="color: #0C0C0C;font-weight: 800;font-size: 15px;width: 530px;background-color: #fff200;border: 2px solid black;">
                {{trans('Team/dOAStasks.2')}}{{ $aStask['stask_name'] }}
                <span style="float: right;" >
				@if( $aStask['status'] =='0')
                        <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/ongoing.png')}}" >
                    @elseif($aStask['status']=='1')
                        <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/pending.png')}}" >
                    @elseif($aStask['status']=='2')
                        <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/finishing.png')}}" id="{{$aStask['stask_id']}}status_img">
                    @else
                        <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/unfinishing.png')}}" id="{{$aStask['stask_id']}}status_img">
                    @endif
			</span>

            </h2>
            <div class="layui-colla-content scroll" style="width: 550px;" >
                <div style="word-wrap:break-word;margin-left: 20px;margin-top: 20px">
                    <p style="word-wrap:break-word;font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #0C0C0C">{{trans('Team/dOAStasks.3')}}</span>{{$aStask['stask_description']}}</p>
                    <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #0C0C0C">{{trans('Team/dOAStasks.4')}}</span>
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
                                <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;width: 250px"><span style="font-size: 18px;color: #0C0C0C">{{trans('Team/dOAStask.5')}}
                                                </span></p>
                                <div class="form-group" STYLE="width:150px;float: left ;margin-top: 20px" >
                                                    <span class="btn btn-success fileinput-button" style="background-color: #fcfcfc;float: left;border:4px solid black;width: 150px" >
                                                    <span style="font-weight: 700;color: #0C0C0C;font-size: 15px;">{{trans('Team/dOAStask.6')}}</span>
                                                    <input type="file" id="file" class="" name="stask_file"  style="opacity: 0;">
                                                     </span>
                                </div>
                                <input type="hidden" name="stask_id" value="{{$aStask['stask_id']}}" >
                                <input type="hidden" name="flag" value="1" >
                                <button id="submit" type="submit" class="layui-btn shadow submit" style="font-size: 12px;border: 4px solid #0C0C0C;color: #0C0C0C;float: left;height: 40px;background-color: #fff200;margin-top: 20px;margin-left: 20px;width: 80px">
                                    {{trans('Team/dOAStask.7')}}
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
                                                    <span style="font-weight: 700;color: #0C0C0C;font-size: 15px;">{{trans('Team/dOAStask.6')}}</span>
                                                    <input type="file" id="file" class="" name="stask_file"  style="opacity: 0;">
                                                     </span>
                            </div>
                            <input type="hidden" name="stask_id" value="{{$aStask['stask_id']}}" >
                            <input type="hidden" name="flag" value="1" >
                            <button id="submit" type="submit" class="layui-btn shadow submit" style="font-size: 12px;border: 4px solid #0C0C0C;color: #0C0C0C;float: left;height: 40px;background-color: #fff200;margin-top: 20px;margin-left: 20px;width: 80px">
                                {{trans('Team/dOAStask.7')}}
                            </button>
                        </form>
                        <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%" ><span style="font-size: 18px;color: #0C0C0C">{{trans('Team/dOAStask.9')}}</span>

                                            <a href={{URL::asset($aStask['infors'][0]['file'])}}  download={{$aStask['infors'][0]['file']}}>
                                            <img src="{{URL::asset('/images/download.png')}}" >
                                            </a>
                            <button id="submit" class="layui-btn submit" style="color: #0C0C0C;background-color: #fcfcfc;border: 2px solid #0C0C0C"  onclick="isHidden('{{$aStask['stask_id']}}'+'submit_box')">
                                {{trans('Team/dOAStask.10')}}
                            </button>
                        @if(empty($aStask['infors'][0]['score']))
                            <p id='{{$aStask['stask_id']}}show_score_p' style="display:none;font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%" >
                            </p>
                        @else
                            <p id='{{$aStask['stask_id']}}show_score_p' style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%" > {{trans('Team/dOAStask.11')}}{{$aStask['infors'][0]['score']}}
                            </p>
                        @endif
                        <div id="{{$aStask['stask_id']}}score" style="display: none">
                            <div id="action_score" style="float: left;width: 100%">
                                <form style="line-height:20px;float:left;color: #0C0C0C;margin-left: 10px;" class="form-group" id="{{$aStask['stask_id']}}">
                                    <div class="form-group" style="width: 250px;float: left">
                                        <div >
                                            <input type="text" required="" class="form-control" id="score_input" width="20px" style="border: 1px solid black">
                                        </div>
                                    </div>
                                    <div class="form-group" style="width: 250px;float: left">
                                       <select class="form-control" id="select_val">
                                           <option value="2">{{trans('Team/dOAStasks.8')}}</option>
                                           <option value="3">{{trans('Team/dOAStasks.9')}}</option>
                                       </select>
                                    </div>
                                    <div style="float: left;margin-left: 30px">
                                        <button id="submit_score" class="layui-btn shadow submit" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fcfcfc" >
                                            {{trans('Team/dOAStasks.5')}}
                                        </button>
                                        <input type="hidden" name="stask_id" value="{{$aStask['stask_id']}}" >
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endunless
                    {{--若已经已经提交子任务，并且当前用户为任务负责人，则允许进行评分以及评论--}}
                    @unless($aStask['status'] =='0')
                        @unless(session('user_id')!=$stask['task_manager_id'])
                        <div class="icons" style="margin-top: 0;float: left;border-radius: 100%;margin-left: 30px" >
                            <ul>
                                <li><a class="comment_icon" onclick="isHidden('{{$aStask['stask_id']}}'+'comment')"> </a></li>
                                <li><a id="score" class="score_icon" onclick="isHidden('{{$aStask['stask_id']}}'+'score')"> </a></li>
                            </ul>
                        </div>
                            @endunless
                    @endunless
                    {{--评论框--}}
                    <div id="{{$aStask['stask_id']}}comment" style="display: none">
                        <div id="action_comment" style="float: left;width: 100%;color: #0C0C0C;margin-left: 10px;line-height: 20px">
                                <div class="form-group" style="width: 300px;float: left;margin-left: 50px">
                                    <div >
                                        <textarea type="text" required="" class="form-control" id="comment_input{{$aStask['stask_id']}}"></textarea>
                                    </div>
                                </div>
                                <div style="float: left;margin-top: 20px;margin-left: 30px">
                                    <button id="submit_comment" class="layui-btn shadow" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fcfcfc" onclick="show('{{$aStask['stask_id']}}')">
                                        {{trans('Team/dOAStasks.6')}}
                                    </button>
                                    <input type="hidden" name="stask_id" value="{{$aStask['stask_id']}}" >
                                </div>
                        </div>
                       <span style="display: none"> {{$i="0"}}</span>
                        <ul class="collection" style="float: left" id="com{{$aStask['stask_id']}}">
                            @unless(empty($aStask['comments']))
                                @foreach($aStask['comments'] as $comment)
                                    <li class="collection-item avatar" style="color: #0C0C0C;height: auto;min-height: 160px;width: 550px;padding-bottom: 10px" >
                                        <div style="float:left;width:100px;height:120px;">
                                            @if(session('user_id')==$comment['commentator_id'])
                                                <img src="{{URL::asset('/uploads/user_profile/'.$comment['commentator_profile'])}}" class="layui-circle" width="65px" height="65px" style="margin-left: 15px;" onclick='javascrtpt:window.location.href="{{url('user/displayInfo')}}"' >
                                            @else
                                                <img src="{{URL::asset('/uploads/user_profile/'.$comment['commentator_profile'])}}" class="layui-circle" width="65px" height="65px" style="margin-left: 15px;" onclick='javascrtpt:window.location.href="{{url('user/displayOthersInfo/'.$comment['commentator_id'])}}"' >
                                            @endif
                                        </div>
                                        <div style="float:left;width: 400px;min-height:120px;font-weight: 800;font-size: 18px;line-height: 40px;height:auto;" >
                                            <textarea  value="" style="width:320px;border: 1px solid transparent;height: 120px;resize: none;" readonly>{{$comment['comment']}}</textarea>
                                            <div style="width: 40px;float: right;margin-right:20px;margin-top:0;" id="del">
                                                <img src="{{URL::asset('/images/delete2.png')}}" >
                                            </div>
                                            <input type="hidden" value="{{$comment['comment_id']}}" name="comment_id">
                                        </div>
                                        <div style="width:400px;height:30px;line-height: 10px;margin-bottom: 10px;">
                                            <p style="font-size: 12px;margin-right: 0px;color: #8D8D8D">
                                                {{ date('Y-m-d H:i:s',$comment['time']) }}
                                            </p>
                                        </div>
                                    </li>

                                @endforeach
                            @endunless
                            <div style="display:none;">
                                <li class="collection-item avatar" style="color: #0C0C0C;height: auto;min-height: 160px;width: 550px;padding-bottom: 10px" id="c{{$i}}{{$aStask['stask_id']}}">
                                    <div style="float:left;width:100px;height:120px;">
                                        <img src="{{URL::asset(session('user_profile'))}}" class="layui-circle" width="65px" height="65px" style="margin-left: 15px;" onclick='javascrtpt:window.location.href="{{url('user/displayInfo')}}"' >
                                    </div>
                                    <div style="float:left;width: 400px;min-height:120px;font-weight: 800;font-size: 18px;line-height: 40px;height:auto;" >
                                        <textarea id="{{$i}}{{$aStask['stask_id']}}" value="" style="width:320px;border: 1px solid transparent;height: 120px;resize: none;"readonly></textarea>
                                        <div style="width: 40px;float: right;margin-right:20px;margin-top:0;" id="del2">
                                            <img src="{{URL::asset('/images/delete2.png')}}" >
                                        </div>
                                    </div>
                                    <div style="width:400px;height:30px;line-height: 10px;margin-bottom: 10px;">
                                        <p style="font-size: 12px;margin-right: 0px;color: #8D8D8D">
                                            {{trans('Team/dOAStasks.7')}}
                                        </p>
                                    </div>
                                    <input id="input{{$i}}" type="hidden" value="{{$aStask['stask_id']}}">
                                    <span style="display: none">{{$i="1"}}</span>
                                </li>
                            </div>
                        </ul>

                    </div>

                </div>
            </div>
        </li>

        @endforeach
    @endforeach
</ul>
<script>
    $('.box').on('click','#submit_score',function(event){
        event.preventDefault();
        var stask_id = $(this).siblings("[name='stask_id']").val();
        var score= $(this).parent().parent().find('#score_input').val();
        if(document.getElementById(stask_id+'show_score_p').style.display=='none'){
            document.getElementById(stask_id+'show_score_p').style.display='block';
        }
        document.getElementById(stask_id+'show_score_p').innerHTML='Score : '+score;
        var name=stask_id+'score';
        document.getElementById(name).style.display='none';
        var status=document.getElementById('select_val').value;
        if(status=='3'){
            $("#"+stask_id+"status_img").attr("src",'{{URL::asset('/images/unfinishing.png')}}');
        }
        else{
            $("#"+stask_id+"status_img").attr("src",'{{URL::asset('/images/finishing.png')}}');
        }
        $.ajax({
            url: "{{url('task/score')}}",
            type: 'post',
            dataType: 'json',
            skin:'demo-class',
            data: {"stask_id":stask_id,'status':status,'score':score,"_token":"{{csrf_token()}}"}
        })
            .done(function(data) {
                layer.msg(data.msg);
            })
            .fail(function(data) {
                layer.msg("Something went wrong!try again later.");
            })
            .always(function() {
                console.log("complete");

            });


    });
    $('.box').on('click','#del',function(event){
        var id=$(this).siblings("[name='comment_id']").val();
        $.ajax({
            url: "{{url('task/deleteComment')}}",
            type: 'post',
            dataType: 'json',
            skin:'demo-class',
            data: {'id':id,"_token":"{{csrf_token()}}"}
        })
            .done(function(data) {
                layer.msg(data.msg);
            })
            .fail(function(data) {
                layer.msg("Something went wrong,try again later");
            })
            .always(function() {
                console.log("complete");
            });
       $(this).parent().parent().remove();
    });
    $('.box').on('click','#del2',function(event){
      layer.msg('Comment deleted!');
        $(this).parent().parent().remove();
    });
</script>
    <script>
        function isHidden(oDiv){
            var vDiv = document.getElementById(oDiv);
            vDiv.style.display = (vDiv.style.display == 'none')?'block':'none';
        }
        function show(stask_id) {
            var stask_id=stask_id;
            /*得到输入框的评论，并加入到预的clone*/
            var comment=document.getElementById('comment_input'+stask_id).value;
            document.getElementById('0'+stask_id).innerHTML=document.getElementById('comment_input'+stask_id).value;
            /*clone得到的是c1*/
            var new_comment=$('#c0'+stask_id).clone();
            /*清空*/
            document.getElementById('comment_input'+stask_id).value=null;
            /*显示*/
            $('#com'+stask_id).prepend(new_comment);
           $.ajax({
                url: "{{url('task/comment')}}",
                type: 'post',
                dataType: 'json',
                skin:'demo-class',
                data: {"stask_id":stask_id,'comment':comment,"_token":"{{csrf_token()}}"}
            })
                .done(function(data) {

                })
                .fail(function(data) {
                    layer.msg("Something went wrong,try again later");
                })
                .always(function() {
                    console.log("complete");
                });
        }

    </script>
@endsection