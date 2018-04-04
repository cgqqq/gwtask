{{--队长查看资源共享页面--}}
@extends('layouts.home')

@section('content')
    <div style="width: 1030px;min-height: 1000px;height: auto" id="profile">
        <div style="width: 1030px;height: 40px;" id="profile"  class="newA" >
                <img class="layui-circle" style="border: 1px solid black;margin-left: 20px;margin-top: 10px" src="{{URL::asset('/images/back.png')}}"  href="javascript:void(0)" onclick="javascript:window.history.go(-1);">
        </div>
        @if(empty($resources))
        @else
        @foreach($resources as $resource)
            @foreach($resource as $item)
        <div style="width:200px;height:400px;float: left;margin: 20px;">
            <div class="card" style="width:200px;height:200px">
                <div class="card-image">
                    <a href={{URL::asset($item['trans_Resource_path'])}} download={{$item['trans_Resource_path']}}>
                        @if($item['type']=='video')
                        <img src="{{URL::asset('/images/video.png')}}" width="200px" height="200px">
                        @elseif($item['type']=='picture')
                        <img src="{{URL::asset('/images/picture.png')}}" width="200px" height="200px">
                        @elseif($item['type']=='pdf')
                            <img src="{{URL::asset('/images/pdf.png')}}" width="200px" height="200px">
                        @elseif($item['type']=='word')
                            <img src="{{URL::asset('/images/word.png')}}" width="200px" height="200px">
                        @elseif($item['type']=='txt')
                            <img src="{{URL::asset('/images/txt.png')}}" width="200px" height="200px">
                        @elseif($item['type']=='zip')
                            <img src="{{URL::asset('/images/zip.png')}}" width="200px" height="200px">
                        @elseif($item['type']=='excel')
                            <img src="{{URL::asset('/images/excel.png')}}" width="200px" height="200px">
                        @elseif($item['type']=='ppt')
                            <img src="{{URL::asset('/images/ppt.png')}}" width="200px" height="200px">
                        @else
                            <img src="{{URL::asset('/images/unknown-file.png')}}" width="200px" height="200px">
                        @endif
                    </a>

                </div>
                <div class="card-content scroll" style="color: #0C0C0C;font-weight: 600;width: 200px;height: 200px;"   >
                    <span class="card-title" style="color: #0C0C0C;font-weight: 900;font-size: 18px;">{{$item['trans_Resource_path']}}</span>
                    <p>{{$item['trans_Resource_intro']}}</p>
                </div>
            </div>
        </div>
            @endforeach
        @endforeach
        {{ $paged->links() }}
        @endif
    </div>
@endsection