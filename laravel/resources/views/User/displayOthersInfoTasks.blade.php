@extends('layouts.home')
@section('content')
    <div style="width: 280px;height: 800px;background-color:#4aaf51;float: left">
        <div style="width: 280px;height: 280px;float: left;color:#0C0C0C;" class="shadow">
            <img class="layui-circle" style="height: 150px;width:150px;margin-top: 12%;margin-left: 22%" src="{{URL::asset('/uploads/user_profile/'.$user_info['user_profile'])}}" >
            <p style=" background-color:#fcfcfc;margin-top: 10px;font-weight: 800;font-size: 16px;text-align:center; width:280px;height:20px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;  ">
                {{$user_info['user_name']}}
            </p>
<div style="font-size: 14px;color: #fcfcfc;margin-left: 20px">
            User ID: {{$user_info['user_id']}}</br>
            Emial Address:  {{$user_info['user_email']}}
</div>

        </div>

        <div style="width: 280px;height:520px;float: left">
            <div class="collection" style="margin-top: 20px;width: 280px;padding: 5px;">
                <a href="{{url('user/displayOthersInfo/'.$user_info['user_id'])}}" class="collection-item" style="font-size: 20px;line-height: 30px">
                    Personal Updatings
                </a>
                <a href="{{url('user/displayOthersInfoTeams?user_id='.$user_info['user_id'])}}" class="collection-item" style="font-size: 20px;line-height: 30px">
                    Teams
                </a>
                <a href="{{url('user/displayOthersInfoTasks?user_id='.$user_info['user_id'])}}" class="collection-item" style="font-size: 20px;line-height: 30px">
                    Tasks
                </a>
                <a href="{{url('user/displayOthersInfoResourceSharings?user_id='.$user_info['user_id'])}}" class="collection-item" style="font-size: 20px;line-height: 30px">
                    Resource Sharings
                </a>

            </div>

        </div>
    </div>
    <div style="width:770px;min-height: 800px;float: left;height: auto;">
        @if(empty($privacy_task))
        <div class="layui-tab" >
            <ul class="layui-tab-title">
                <li class="layui-this" style="background-color: #4aaf51;">{{$user_info['user_name']}} Has Published These Tasks</li>
                <li style="background-color: #4aaf51;">{{$user_info['user_name']}} Participates In These Tasks</li>
            </ul>
            <div class="layui-tab-content" style="color: #0C0C0C">
                <div class="layui-tab-item layui-show">
                    @if(empty($transData1))
                        <div style="text-align: center;margin-top: 40%">
                            <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                                <img src="{{URL::asset('/images/empty.png')}}" > No Task Has Been Published Yet!
                            </p>
                        </div>
                    @else
                    <div class="layui-collapse scroll" lay-filter="test" style="width: 770px;height: 770px">
                @foreach($transData1 as $task)
                    <div class="layui-colla-item" style="width: 730px;">
                        <h2 class="layui-colla-title" style="color: #0C0C0C;font-weight: 800;font-size: 15px;width: 730px;background-color: #34bf49">
                            {{ $task['task_name'] }}  <span  style="color: #fcfcfc"> From </span> {{$task['team_name']}}
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
                            <div style="min-height: 200px;height: auto;">
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
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                    </div>
                @endforeach
                    </div>
                    @endif
                </div>
                <div class="layui-tab-item">
                    @if(empty($transData2))
                        <div style="text-align: center;margin-top: 40%">
                            <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                                <img src="{{URL::asset('/images/empty.png')}}" >No Task Has Been Joined In Yet!
                            </p>
                        </div>
                    @else
                        <div class="layui-collapse scroll" lay-filter="test" style="width: 770px;height: 770px">
                            @foreach($transData2 as $task)
                                <div class="layui-colla-item" style="width: 730px;">
                                    <h2 class="layui-colla-title" style="color: #0C0C0C;font-weight: 800;font-size: 15px;width: 730px;background-color: #34bf49">
                                        {{ $task['task_name'] }}  <span  style="color: #fcfcfc"> From </span> {{$task['team_name']}}
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
                                        <div style="min-height: 200px;height: auto;">
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
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>

                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
            </div>
        </div>
        @else
            <div style="text-align: center;margin-top: 40%">
                <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                    <img src="{{URL::asset('/images/lock.png')}}" > User {{$user_info['user_name']}} Has Locked This Task Page!
                </p>
            </div>
        @endif
    </div>

@endsection
