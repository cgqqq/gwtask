@extends('layouts.home')

@section('header')

@endsection

@section('content')
    <div style="display: table">
        <div class="layui-tab" >
            <ul class="layui-tab-title">
                <li class="layui-this" style="background-color: #4aaf51;"> Friends' Updatings</li>
                <li style="background-color: #4aaf51;">Teams' Updatings</li>
            </ul>
            <div class="layui-tab-content" style="color: #0C0C0C;">
                <div class="layui-tab-item layui-show" style="">
                    <div style="width:770px;height: 200px;"  >
                        <form style="line-height:20px;margin: 20px;padding:10px;width: 750px;float:left;color: #0C0C0C;cursor: hand;" class="form-group" enctype="multipart/form-data" method="post" action="{{ url('home') }}">
                            {{ csrf_field() }}
                            <div class="form-group" style="margin-bottom: 10px;" >
                                <div class="col-md-6">
                                    <textarea type="text" name="content" id="content" class="form-control" style="width: 600px;height: 100px;max-height: 100px;max-width: 550px;" required></textarea>
                                </div>
                            </div>
                            <div>
                                <button id="submit" type="submit" class="layui-btn shadow submit" style="border: 2px solid #0C0C0C;color: #0C0C0C;margin-top: 30px;float: right">
                                    Submit
                                </button>
                                <input type="hidden" name="user_id" value="{{session('user_id')}}">
                            </div>
                            <div>
                <span class="btn btn-success fileinput-button" style="background-color: transparent;margin-top: 5px;float: right;margin-right: 540px;border: solid 1px transparent" >
                    <span style="font-weight: 700;color: #0C0C0C;font-size: 15px">Upload Resource</span>
                    <input type="file" id="resource" class="" name="resource"  style="opacity: 0;">
                </span>
                            </div>
                            <input type="hidden" name="flag" value='1' id="flag">
                            <input type="hidden" name="updater_id" value="{{session('user_id')}}" >
                        </form>
                    </div>
                    <div style="width: 1030px;float: left;min-height: 550px;height: auto;" >
                        @if(empty($friendsUpdatings))
                            <p style="color: #0C0C0C;font-size: 20px;font-weight: 900;margin-left:350px;margin-top: 160px">
                                No One Has Posted Updating Yet!
                            </p>
                        @else

                            <ul class="collection" id="flow">
                                @foreach($friendsUpdatings as $friendsUpdating)
                                    <li class="collection-item avatar" style="color: #0C0C0C;margin-bottom:20px;height: auto;min-height: 80px;display: table;width: 1030px " >
                                        @if(session('user_id')==$friendsUpdating['updater_id'])
                                            <img src="{{URL::asset('/uploads/user_profile/'.$friendsUpdating['user_profile'])}}" class="layui-circle" width="65px" height="65px" style="margin-left: 15px;" onclick='javascrtpt:window.location.href="{{url('user/displayInfo')}}"' >
                                        @else
                                            <img src="{{URL::asset('/uploads/user_profile/'.$friendsUpdating['user_profile'])}}" class="layui-circle" width="65px" height="65px" style="margin-left: 15px;" onclick='javascrtpt:window.location.href="{{url('user/displayOthersInfo/'.$friendsUpdating['updater_id'])}}"' >
                                        @endif
                                        <div style="float:right;width: 90%;font-weight: 800;font-size: 18px;word-wrap: break-word;line-height: 40px;display: table;padding-right: 40px;color: black" >
                                            {{$friendsUpdating['user_name']}} : {{$friendsUpdating['content']}}
                                        </div>
                                        <div style="width:760px;float: left ;font-size: 12px;color:#0C0C0C;line-height: 10px" >
                                            @unless($friendsUpdating['type']!='sResource')

                                                <p style=" font-weight:700;font-size:15px;" >
                                                    Resource Download :
                                                    <a href={{URL::asset($friendsUpdating['resource'])}} download={{$friendsUpdating['resource']}}>
                                                        <img src="{{URL::asset('/images/click.png')}}" >
                                                    </a>
                                                </p>

                                            @endunless

                                            <p style="font-size: 12px;margin-right: 0px;margin-top: 10px">
                                                {{ date('Y-m-d H:i:s',$friendsUpdating['time']) }}
                                            </p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                        @endif
                    </div>
                    <div>
                        @unless(empty($paged))
                            {{$paged->links()}}
                        @endunless
                    </div>
                </div>
                <div class="layui-tab-item">
                    @if(empty($teamUpdatings))
                        <p style="color: #0C0C0C;font-size: 20px;font-weight: 900;margin-left:350px;margin-top: 160px">
                            No Team Has Posted Updating Yet!
                        </p>
                    @else
                        <ul class="collection" style="width: 1050px;float: left">
                            @foreach($teamUpdatings as $teamUpdating)
                                <li class="collection-item shadow" style="width:1020px;height:auto;min-height:180px;margin-left: 5px;margin-top: 10px;"  >
                                    <div style="height:100px;width: 130px;float: left;">
                                        @if(session('user_id')==$teamUpdating['uploader_id'])
                                            <img src="{{URL::asset('/uploads/user_profile/'.$teamUpdating['user_profile'])}}" class="layui-circle" width="65px" height="65px" style="margin-left: 15px;" onclick='javascrtpt:window.location.href="{{url('user/displayInfo')}}"' >
                                        @else
                                            <img src="{{URL::asset('/uploads/user_profile/'.$teamUpdating['user_profile'])}}" class="layui-circle" width="65px" height="65px" style="margin-left: 15px;" onclick='javascrtpt:window.location.href="{{url('user/displayOthersInfo/'.$teamUpdating['uploader_id'])}}"' >
                                        @endif

                                    </div>
                                    <div style="font-weight:600;font-size:18px;color:#0C0C0C;height:auto;min-height:140px;width: 700px;float: left;word-wrap:break-word;line-height: 30px;position:relative ;margin-top: 10px">
                                    <span style="color: #34bf49;font-weight: 800">
                                    {{$teamUpdating['user_name']}} From {{$teamUpdating['team_name']}}:</span>
                                        <br>
                                        {{$teamUpdating['content']}}

                                        @unless($teamUpdating['resource']==null)
                                            <p style=" font-weight:600;font-size:15px;color:#0C0C0C;margin-bottom: 5px; ">
                                                Resource Download :

                                                <a href={{URL::asset($teamUpdating['resource'])}} download={{$teamUpdating['resource']}}>
                                                    <img src="{{URL::asset('/images/click.png')}}" >
                                                </a>
                                            </p>
                                        @endunless
                                        <p style="font-size: 10px;color:#8D8D8D;float:right;position:absolute;top:130px;padding: 0px;margin-right: 0px; ">
                                            {{ date('Y-m-d H:i:s',$teamUpdating['time']) }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>


    </div>
    </div>
    <script>

    </script>

@endsection
