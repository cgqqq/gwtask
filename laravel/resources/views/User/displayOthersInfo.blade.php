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
    <div style="width:770px;height: 800px;float: left">

        <div style="width:770px;height:800px;" class="scroll">
            @if(empty($privacy_updating))
                @if(empty($personalUpdatings))
                <div style="text-align: center;margin-top: 40%">
                    <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                        <img src="{{URL::asset('/images/empty.png')}}" >{{$user_info['user_name']}} Has Not Posted Any Updating Yet!
                    </p>
                </div>
            @else
                    <ul class="collection">
                    @foreach($personalUpdatings as $personalUpdating)
                        <li class="collection-item avatar shadow" style="color: #0C0C0C;margin-bottom:20px;height: auto;min-height: 80px;display: table;width: 760px" >
                            <img src="{{URL::asset('/uploads/user_profile/'.$user_info['user_profile'])}}" alt="" class="layui-circle" width="50px" height="50px" >
                            <div style="float:right;width: 90%;font-weight: 800;font-size: 18px;word-wrap: break-word;line-height: 40px;display: table;padding-right: 40px" >
                                {{$personalUpdating['content']}}
                            </div>
                            <div style="width:760px;float: left ;font-size: 12px;color:#0C0C0C;line-height: 10px" >
                                @unless($personalUpdating['type']!='sResource')

                                    <p style=" font-weight:700;font-size:15px;" >
                                        Resource Download :
                                        <a href={{URL::asset($personalUpdating['resource'])}} download={{$personalUpdating['resource']}}>
                                            <img src="{{URL::asset('/images/click.png')}}" >
                                        </a>
                                    </p>

                                @endunless

                                <p style="font-size: 12px;margin-right: 0px;margin-top: 10px">
                                    {{ date('Y-m-d H:i:s',$personalUpdating['time']) }}
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
                @endif
            @else
                <div style="text-align: center;margin-top: 40%">
                    <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                        <img src="{{URL::asset('/images/lock.png')}}" > User {{$user_info['user_name']}} Has Locked This Personal Updating Page!
                    </p>
                </div>
            @endif
        </div>
    </div>
    <script>
    </script>
@endsection