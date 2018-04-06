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
    <div style="width:770px;min-height: 800px;float: left;height: auto;" class="scroll">
        @foreach($resources as $resource)
            <div style="width:200px;height:400px;float: left;margin-top: 20px;margin-left: 40px">
                <div class="card" style="width:200px;height:200px">
                    <div class="card-image" id="profile">
                        <a href={{URL::asset($resource['resource'])}} download={{$resource['resource']}}>
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
    </div>

@endsection
