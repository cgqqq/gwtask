@extends('layouts.home')
@section('content')
    <div style="width: 280px;height: 800px;background-color:#4aaf51;float: left">
        <div style="width: 280px;height: 280px;float: left;color:#0C0C0C;" class="shadow">
            <img class="layui-circle" style="height: 150px;width:150px;margin-top: 12%;margin-left: 22%" src="{{URL::asset('/uploads/user_profile/'.$user_info['user_profile'])}}" >
            <p style=" background-color:#fcfcfc;margin-top: 10px;font-weight: 800;font-size: 16px;text-align:center; width:280px;height:20px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;  ">
                {{$user_info['user_name']}}
            </p>
            <div style="font-size: 14px;color: #fcfcfc;margin-left: 20px">
                {{trans('User/displayOthersInfo.1')}}{{$user_info['user_id']}}</br>
                {{trans('User/displayOthersInfo.2')}}{{$user_info['user_email']}}
            </div>

        </div>

        <div style="width: 280px;height:520px;float: left">
            <div class="collection" style="margin-top: 20px;width: 280px;padding: 5px;">
                <a href="{{url('user/displayOthersInfo/'.$user_info['user_id'])}}" class="collection-item" style="font-size: 20px;line-height: 30px">
                    {{trans('User/displayOthersInfo.3')}}
                </a>
                <a href="{{url('user/displayOthersInfoTeams?user_id='.$user_info['user_id'])}}" class="collection-item" style="font-size: 20px;line-height: 30px">
                    {{trans('User/displayOthersInfo.4')}}
                </a>
                <a href="{{url('user/displayOthersInfoTasks?user_id='.$user_info['user_id'])}}" class="collection-item" style="font-size: 20px;line-height: 30px">
                    {{trans('User/displayOthersInfo.5')}}
                </a>
                <a href="{{url('user/displayOthersInfoResourceSharings?user_id='.$user_info['user_id'])}}" class="collection-item" style="font-size: 20px;line-height: 30px">
                    {{trans('User/displayOthersInfo.6')}}
                </a>

            </div>

        </div>
    </div>
    <div style="width:770px;min-height: 800px;float: left;height: auto;">

        <div class="layui-tab" >
            <ul class="layui-tab-title">
                <li class="layui-this" style="background-color: #4aaf51;">{{$user_info['user_name']}} {{trans('User/displayOthersInfo.22')}}</li>
                <li style="background-color: #4aaf51;">{{$user_info['user_name']}} {{trans('User/displayOthersInfo.23')}}</li>
            </ul>
            <div class="layui-tab-content" style="color: #0C0C0C">
                <div class="layui-tab-item layui-show" >
                    <div class="portfolio-grid portfolioContainer scroll"  STYLE="width: 100%;height: 750px;">
                        @if($privacy_team_created=='0')
                        @if(empty($teams))
                            <div style="text-align: center;margin-top: 40%">
                                <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                                    <img src="{{URL::asset('/images/empty.png')}}" >{{$user_info['user_name']}} {{trans('User/displayOthersInfo.24')}}
                                </p>
                            </div>
                        @else
                        <ul id="thumbs" class="col3"  style="width: 100%;height: 100%; ">
                            @foreach($teams as $teams)
                                <li style="width: 350px;height: 265px;margin-left:10px;margin-bottom: 60px;margin-top: 30px;">
                                    <div class="portfolio-image-wrapper" style="position: relative">
                                        <img src="{{URL::asset('/images/team.png')}}" alt=""  style="border:3px dashed #000;">
                                        <div class="item-info-overlay">

                                            <div style="width: 280px;height: 40px;white-space:nowrap;overflow: hidden;text-overflow: ellipsis;">
                                                <h3 class="text-white semi-bold p-t-60 project-title " style="color: #fff200;font-weight: 900;">
                                                    {{trans('User/displayOthersInfo.25')}}{{ $teams['team_name'] }}</h3>
                                            </div>
                                            <P> </P>
                                            <div style="width: 280px;height: 30px;white-space:nowrap;overflow: hidden;text-overflow: ellipsis;">
                                                <p class="project-description">
                                                    {{trans('User/displayOthersInfo.26')}}{{ $teams['user_name'] }}</p>
                                            </div>
                                            <div style="width: 280px;height: 20px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap">
                                                <p class="project-description">
                                                    {{trans('User/displayOthersInfo.27')}}{{ $teams['team_info'] }}</p>
                                            </div>
                                            <div style="width: 300px;	" class="project-description">
                                                <button class="view_icon" onclick='javascrtpt:window.location.href="{{url('team/displayOne',['team_name'=> $teams['team_name']])}}"'> </button>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="item-info" style="position: inherit">
                                        <h4 class="text-dark no-margin p-t-10">
                                            {{trans('User/displayOthersInfo.25')}}{{ $teams['team_name'] }}</h4>
                                        <p>
                                            {{trans('User/displayOthersInfo.28')}}{{ date('Y-m-d H:i:s',$teams['created_at']) }}</p>

                                    </div>
                                    @endforeach
                                </li>
                        </ul>
                        @endif
                        @else
                            <div style="text-align: center;margin-top: 40%">
                                <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                                    <img src="{{URL::asset('/images/lock.png')}}" > {{trans('User/displayOthersInfo.9')}} {{$user_info['user_name']}} {{trans('User/displayOthersInfo.21')}}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="layui-tab-item" >
                    <div class="portfolio-grid portfolioContainer scroll"  STYLE="width: 100%;height: 750px;">
                        @if($privacy_team_joined=='0')
                        @if(empty($myTeams))
                            <div style="text-align: center;margin-top: 40%">
                                <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                                    <img src="{{URL::asset('/images/empty.png')}}" >{{$user_info['user_name']}} {{trans('User/displayOthersInfo.29')}}
                                </p>
                            </div>
                        @else
                        <ul id="thumbs" class="col3"  style="width: 100%;height: 100%; ">
                            @foreach($myTeams as $myTeams)
                                <li style="width: 350px;height: 265px;margin-left:10px;margin-bottom: 60px;margin-top: 30px;">
                                    <div class="portfolio-image-wrapper" style="position: relative">
                                        <img src="{{URL::asset('/images/team.png')}}" alt=""  style="border:3px dashed #000;">
                                        <div class="item-info-overlay">

                                            <div style="width: 280px;height: 40px;white-space:nowrap;overflow: hidden;text-overflow: ellipsis;">
                                                <h3 class="text-white semi-bold p-t-60 project-title " style="color: #fff200;font-weight: 900;">
                                                    {{trans('User/displayOthersInfo.25')}}{{ $myTeams['team_name'] }}</h3>
                                            </div>
                                            <P> </P>
                                            <div style="width: 280px;height: 30px;white-space:nowrap;overflow: hidden;text-overflow: ellipsis;">
                                                <p class="project-description">
                                                    {{trans('User/displayOthersInfo.26')}}{{ $myTeams['user_name'] }}</p>
                                            </div>
                                            <div style="width: 280px;height: 20px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap">
                                                <p class="project-description">
                                                    {{trans('User/displayOthersInfo.27')}}{{ $myTeams['team_info'] }}</p>
                                            </div>
                                            <div style="width: 300px;	" class="project-description">
                                                <button class="view_icon" onclick='javascrtpt:window.location.href="{{url('team/displayOne',['team_name'=> $myTeams['team_name']])}}"'> </button>
                                            </div>

                                        </div>

                                    </div>
                                    <div class="item-info" style="position: inherit">
                                        <h4 class="text-dark no-margin p-t-10">
                                            {{trans('User/displayOthersInfo.25')}}{{ $myTeams['team_name'] }}</h4>
                                        <p>
                                            {{trans('User/displayOthersInfo.28')}}{{ date('Y-m-d H:i:s',$myTeams['created_at']) }}</p>

                                    </div>
                                    @endforeach
                                </li>
                        </ul>
                        @endif
                            @else
                            <div style="text-align: center;margin-top: 40%">
                                <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                                    <img src="{{URL::asset('/images/lock.png')}}" > {{trans('User/displayOthersInfo.9')}}{{$user_info['user_name']}} {{trans('User/displayOthersInfo.21')}}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
