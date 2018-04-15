@extends('layouts.home')

@section('header')

@endsection

@section('content')
    <div style="display: table">
        <div class="layui-tab" >
            <ul class="layui-tab-title">
                <li class="layui-this" style="background-color: #4aaf51;"> {{trans('Home/home.1')}}</li>
                <li style="background-color: #4aaf51;">{{trans('Home/home.2')}}</li>
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
                                    {{trans('Home/home.3')}}
                                </button>
                                <input type="hidden" name="user_id" value="{{session('user_id')}}">
                            </div>
                            <div>
                <span class="btn btn-success fileinput-button" style="background-color: transparent;margin-top: 5px;float: right;margin-right: 540px;border: solid 1px transparent" >
                    <span style="font-weight: 700;color: #0C0C0C;font-size: 15px">{{trans('Home/home.4')}}</span>
                    <input type="file" id="resource" class="" name="resource"  style="opacity: 0;">
                </span>
                            </div>
                            <input type="hidden" name="flag" value='1' id="flag">
                            <input type="hidden" name="updater_id" value="{{session('user_id')}}" >
                        </form>
                    </div>
                    <div style="width: 1030px;float: left;min-height: 550px;height: auto;" >
                        @if(empty($friendsUpdatings))
                            <div style="text-align: center;margin-top: 20%">
                                <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                                    <img src="{{URL::asset('/images/empty.png')}}" >{{trans('Home/home.5')}}
                                </p>
                            </div>
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
                                            <div style="width:20px;float: right;margin-left: 5px;" id="del_user_updating"> <img src="{{URL::asset('/images/delete2.png')}}" class="delete_tran"></div>
                                            <input type="hidden" value="{{$friendsUpdating['updating_id']}}" name="id">
                                        </div>
                                        <div style="width:760px;float: left ;font-size: 12px;color:#0C0C0C;line-height: 10px" >
                                            @unless($friendsUpdating['type']!='sResource')

                                                <p style=" font-weight:700;font-size:15px;" >
                                                    {{trans('Home/home.6')}}
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
                        <div style="text-align: center;margin-top: 40%;margin-left:300px; ">
                            <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                                <img src="{{URL::asset('/images/empty.png')}}" >{{trans('Home/home.7')}}
                            </p>
                        </div>
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
                                    {{$teamUpdating['user_name']}} {{trans('Home/home.8')}} {{$teamUpdating['team_name']}}:</span>
                                        <br>
                                        {{$teamUpdating['content']}}

                                        @unless($teamUpdating['resource']==null)
                                            <p style=" font-weight:600;font-size:15px;color:#0C0C0C;margin-bottom: 5px; ">
                                                {{trans('Home/home.9')}}

                                                <a href={{URL::asset($teamUpdating['resource'])}} download={{$teamUpdating['resource']}}>
                                                    <img src="{{URL::asset('/images/click.png')}}" >
                                                </a>
                                            </p>
                                        @endunless
                                        <p style="font-size: 10px;color:#8D8D8D;float:right;position:absolute;top:130px;padding: 0px;margin-right: 0px; ">
                                            {{ date('Y-m-d H:i:s',$teamUpdating['time']) }}{{$teamUpdating['uploading_id']}}
                                        </p>
                                    </div>
                                    <div style="width:20px;float: right;margin-left: 5px;" id="del_team_uploading"> <img src="{{URL::asset('/images/delete2.png')}}" class="delete_tran"></div>
                                    <input type="hidden" value="{{$teamUpdating['uploading_id']}}" name="t_id">
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
        $('.collection-item').on('click','#del_user_updating',function(event){
            var id=$(this).siblings("[name='id']").val();
            $.ajax({
                url: "{{url('user/deleteUserUpdating')}}",
                type: 'post',
                dataType: 'json',
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
        $('.collection-item').on('click','#del_team_uploading',function(event){
            var id=$(this).siblings("[name='t_id']").val();
            $.ajax({
                url: "{{url('team/deleteTeamUploading')}}",
                type: 'post',
                dataType: 'json',
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
    </script>

@endsection
