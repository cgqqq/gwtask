<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/layui.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" type="text/css" media="all" >
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" type="text/css" media="all" >
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}" type="text/javascript"></script>
<!-- <script src="{{ asset('js/') }}" type="text/javascript"></script> -->
    <script src="{{ asset('layui.js') }}" charset="utf-8"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        function isHidden(oDiv){
            var vDiv = document.getElementById(oDiv);
            vDiv.style.display = (vDiv.style.display == 'none')?'block':'none';
        }
        $('.action_score').on('click','#submit_score',function(event){
            event.preventDefault();
            var stask_id = $(this).siblings("[name='stask_id']").val();
            $.ajax({
                url: "{{ url('task/score') }}",
                type: 'post',
                dataType: 'json',
                skin:'demo-class',
                data: {"stask_id":stask_id,'score':text,"_token":"{{csrf_token()}}"}
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
            layer.close(index);
        });

    </script>
    <style>
        body .a-class .layui-layer-btn .layui-layer-btn0{background:#34bf49;border: 1px solid #34bf49}
    </style>


</head>
{{--队长查看子任务页面--}}
{{--
@extends('team.'.$AuthOrNot)
@section('team_content')
    <div style="min-height: 200px;height: auto;word-wrap:break-word;margin-left: 150px;margin-top: 30px">

    <p style="font-size: 25px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 25px;color: #8D8D8D">Sub Task : </span>{{$stask_info['stask_name']}}</p>
    <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #8D8D8D">Detail : </span>{{$stask_info['stask_description']}}</p>
    <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #8D8D8D">Status : </span>
        @if( $stask_info['status'] =='0')
            <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/ongoing.png')}}" >
            未提交
        @elseif($stask_info['status'] =='1')
            <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/pendding.png')}}" >
            待审核
        @elseif($stask_info['status'] =='2')
            <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/finishing.png')}}" >
            @else
            未通过
        @endif

    </p>

    </div>
    <span style="display: none">{{$i='0'}}</span>
    @foreach($stask_members as $stask_member)
        @unless($stask_member['res_id']==session('user_id'))
            <span style="display: none">{{$i='1'}}</span>
        @endunless
    @endforeach
    @if(!$i=='1')
        <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #8D8D8D">Responsible Teammate(s) : </span>
        @foreach($stask_members as $stask_member)
            @if(session('user_id')==$stask_member['res_id'])
            <a href="{{url('user/displayInfo')}}">{{$stask_member['res_name']}}</a>
                @else
            <a href="{{url('user/displayOthersInfo/'.$stask_member['res_id'])}}">{{$stask_member['res_name']}}</a>
                @endif
        @endforeach
        </p>
    @else
    <form class="form-group"  style="color: #333333;font-size: large;width: 90%;line-height:20px;float:left;margin-left:150px;color: #0C0C0C;cursor: hand;height: 200px;" id="submit_box" action="post">
        {{ csrf_field() }}
        <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;width: 250px"><span style="font-size: 18px;color: #8D8D8D">Sub Task Submission : </span></p>
        <div class="form-group" STYLE="width:150px;float: left ;margin-top: 20px" >
                <span class="btn btn-success fileinput-button" style="background-color: #fcfcfc;float: left;border:4px solid black;width: 150px" >
                    <span style="font-weight: 700;color: #0C0C0C;font-size: 15px;">Upload File</span>
                    <input type="file" id="file" class="" name="file"  style="opacity: 0;">
                </span>
        </div>
        <input type="hidden" name="stask_id" value="{{$stask_info['stask_id']}}" >
    </form>
    @endif
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
            });
        });
    </script>
    {{ $paged->links() }}
@endsection--}}
<body style="background-color: #34bf49">
<div style="width: 100%;height: 100%;padding:0px;background-color: #34bf49">
    <div style="min-height: 200px;height: auto;word-wrap:break-word;margin-left: 150px;margin-top: 30px">

        <p style="font-size: 25px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 25px;color: #fcfcfc">Sub Task : </span>{{$stask_info['stask_name']}}</p>
        <p style="word-wrap:break-word;font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #fcfcfc">Detail : </span>{{$stask_info['stask_description']}}</p>
        <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #fcfcfc">Status : </span>
            @if( $stask_info['status'] =='0')
                <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/ongoing.png')}}" >
                未提交
            @elseif($stask_info['status'] =='1')
                <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/pending.png')}}" >
                待审核
            @elseif($stask_info['status'] =='2')
                <img class="layui-circle" style="height: 30px;width:30px;background-color: #fcfcfc;border: 2px solid #fcfcfc" src="{{URL::asset('/images/finishing.png')}}" >
            @else
                <img class="layui-circle" style="height: 30px;width:30px;" src="{{URL::asset('/images/unfinishing.png')}}" >
                未通过
            @endif

        </p>
        <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #fcfcfc">Responsible Teammate(s) : </span>
        <div class="newA">
            @foreach($stask_members as $stask_member)
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
        @foreach($stask_members as $stask_member)
            @unless($stask_member['res_id']!=session('user_id'))
                <span style="display: none">{{$i='1'}}</span>
            @endunless
        @endforeach
        @if($i=='2')

            @else
                @if($stask_info['status'] =='0')
                <form class="form-group"  enctype="multipart/form-data" style="color: #333333;font-size: large;width: 90%;line-height:20px;float:left;margin-left:10px;color: #0C0C0C;cursor: hand;height: 200px;" id="submit_box" method="post" action="">
                    {{ csrf_field() }}
                    <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;width: 250px"><span style="font-size: 18px;color: #fcfcfc">Sub Task Submission :
                    </span></p>
                    <div class="form-group" STYLE="width:150px;float: left ;margin-top: 20px" >
                <span class="btn btn-success fileinput-button" style="background-color: #fcfcfc;float: left;border:4px solid black;width: 150px" >
                    <span style="font-weight: 700;color: #0C0C0C;font-size: 15px;">Select File</span>
                    <input type="file" id="file" class="" name="stask_file"  style="opacity: 0;">
                </span>
                    </div>
                    <input type="hidden" name="stask_id" value="{{$stask_info['stask_id']}}" >
                    <input type="hidden" name="flag" value="1" >
                    <button id="submit" type="submit" class="layui-btn shadow submit" style="font-size: 12px;border: 4px solid #0C0C0C;color: #0C0C0C;float: left;height: 40px;background-color: #fff200;margin-top: 20px;margin-left: 20px;width: 80px">
                        Submit
                    </button>
                </form>
                @else
                <form class="form-group test1"  enctype="multipart/form-data" style="color: #333333;font-size: large;width: 90%;line-height:20px;float:left;margin-left:10px;color: #0C0C0C;cursor: hand;height: 200px;display: none" id="submit_box" method="post" action="">
                {{ csrf_field() }}
                <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;width: 250px"><span style="font-size: 18px;color: #fcfcfc">Sub Task Submission :
                    </span></p>
                <div class="form-group" STYLE="width:150px;float: left ;margin-top: 20px" >
                <span class="btn btn-success fileinput-button" style="background-color: #fcfcfc;float: left;border:4px solid black;width: 150px" >
                    <span style="font-weight: 700;color: #0C0C0C;font-size: 15px;">Select File</span>
                    <input type="file" id="file" class="" name="stask_file"  style="opacity: 0;">
                </span>
                </div>
                <input type="hidden" name="stask_id" value="{{$stask_info['stask_id']}}" >
                <input type="hidden" name="flag" value="1" >
                <button id="submit" type="submit" class="layui-btn shadow submit" style="font-size: 12px;border: 4px solid #0C0C0C;color: #0C0C0C;float: left;height: 40px;background-color: #fff200;margin-top: 20px;margin-left: 20px;width: 80px">
                    Submit
                </button>
            </form>
                @endif
            @endif
        @unless($stask_info['status'] =='0')
        <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%" ><span style="font-size: 18px;color: #fcfcfc">Submitted File :
                <a href={{URL::asset($file_path)}}  download={{$file_path}}>
                    <span>  </span>
                <img src="{{URL::asset('/images/download.png')}}" >
                </a></span>
            <button id="submit_score" class="layui-btn submit" style="color: #fcfcfc;background-color: transparent" onclick="isHidden('submit_box')">
                reload
            </button>
        </p>
        {{--<div id="action_score" style="display: none;float: left;width: 100%">
                <form style="line-height:20px;float:left;color: #0C0C0C;margin-left: 10px;" class="form-group" >
                    <div class="form-group">
                        <label class="control-label" ><span style="font-size: 20px;color: #fcfcfc">Score :</span> </label>
                        <div >
                            <input type="text" required="" class="form-control" width="20px">
                        </div>
                    </div>
                    <div style="float: left">
                        <button id="submit_score" class="layui-btn shadow submit" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fcfcfc">
                            Save
                        </button>
                        <input type="hidden" name="stask_id" value="{{$stask_info['stask_id']}}" >
                    </div>
                </form>
        </div>--}}
            @unless(empty($stask_info['score']))
            <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #fcfcfc">Score : </span> {{$stask_info['score']}}</p>
            @endunless
        @endunless
        <div class="action_box" style="float: left;display: block" >
       {{-- @if(session('user_id')==$task_manager_id)
            <button id="score" type="submit" class="layui-btn shadow submit" style="font-size: 12px;border: 4px solid #0C0C0C;color: #0C0C0C;float: left;height: 40px;background-color: #fff200;margin-top: 20px;margin-left: 20px;width: 100px" onclick="isHidden('action_score')">
                score
            </button>
            <input type="hidden" name="stask_id" value="{{$stask_info['stask_id']}}" >
            <button id="comment" type="submit" class="layui-btn shadow submit" style="font-size: 12px;border: 4px solid #0C0C0C;color: #0C0C0C;float: left;height: 40px;background-color: #fff200;margin-top: 20px;margin-left: 20px;width: 100px" onclick="isHidden('action_comment')">
                comment
            </button>

        @else
        @endif--}}
        </div>
    </div>
</div>


</body>
</html>
