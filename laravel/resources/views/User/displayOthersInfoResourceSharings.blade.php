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
    <div style="width:770px;min-height: 800px;float: left;height: auto;" class="scroll">
        @unless($download_resource=='0')
        <div style="width:770px;height: 40px; text-align: center;display: none" id="tip">
            <p style="font-size: 12px;color:#800000;">
                {{trans('User/displayOthersInfo.9')}} {{$user_info['user_name']}} {{trans('User/displayOthersInfo.11')}}
            </p>
        </div>
        @endunless
        @if(empty($resources))
            <div style="text-align: center;margin-top: 40%">
            <p style="color: #bbbbb7;font-size: 20px;font-weight: 900;">
                <img src="{{URL::asset('/images/empty.png')}}" >{{trans('User/displayOthersInfo.12')}}
            </p>
            </div>
        @else
        @foreach($resources as $resource)
            <div style="width:200px;height:400px;float: left;margin-top: 20px;margin-left: 40px">
                <div class="card" style="width:200px;height:200px">
                    <div class="card-image" id="profile">
                        @if($download_resource=='1')
                        <a href="javascript:void(0);"  onclick="tip()">
                            @else
                        <a href={{URL::asset($resource['resource'])}} download={{$resource['resource']}}>
                            @endif
                            @if($resource['type']=='video')
                                <img src="{{URL::asset('/images/video.png')}}" width="200px" height="200px">
                            @elseif($resource['type']=='picture')
                                <img src="{{URL::asset('/images/picture.png')}}" width="200px" height="200px">
                            @elseif($resource['type']=='pdf')
                                <img src="{{URL::asset('/images/pdf.png')}}" width="200px" height="200px">
                            @elseif($resource['type']=='word')
                                <img src="{{URL::asset('/images/word.png')}}" width="200px" height="200px">
                            @elseif($resource['type']=='txt')
                                <img src="{{URL::asset('/images/txt.png')}}" width="200px" height="200px">
                            @elseif($resource['type']=='zip')
                                <img src="{{URL::asset('/images/zip.png')}}" width="200px" height="200px">
                            @elseif($resource['type']=='excel')
                                <img src="{{URL::asset('/images/excel.png')}}" width="200px" height="200px">
                            @elseif($resource['type']=='ppt')
                                <img src="{{URL::asset('/images/ppt.png')}}" width="200px" height="200px">
                            @else
                                <img src="{{URL::asset('/images/unknown-file.png')}}" width="200px" height="200px">
                            @endif
                        </a>

                    </div>
                    <div class="card-content scroll" style="color: #0C0C0C;font-weight: 600;width: 200px;height: 200px;"   >
                        <span class="card-title" style="color: #0C0C0C;font-weight: 900;font-size: 18px;">{{$resource['name']}}</span>
                        <p>{{$resource['content']}}</p>
                        <p style="font-size: 12px;margin-right: 0px;margin-top: 10px">
                            {{ date('Y-m-d H:i:s',$resource['time']) }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
        @endif
    </div>
<script>
    function tip() {
        document.getElementById('tip').style.display='block';
    }
</script>
@endsection
