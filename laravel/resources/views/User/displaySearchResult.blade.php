@extends('layouts.home')

@section('content')


    @if($result)
        <span style="color: #000;font-size: 20px">
            Result
       <fieldset class="layui-elem-field" STYLE="margin-right: 10px;height:300px;margin-top: 20px">

            <div class="layui-field-box">
                <div class="card" style="width: 200px;height: 100px;">
                    <div class="card-image">
                        @if($result[0]['user_id']==session('user_id'))
                        <img class="activator" src="{{URL::asset('/uploads/user_profile/'.$result[0]['user_profile'])}}"  style="width: 200px;height: 200px;"  onclick='javascrtpt:window.location.href="{{url('user/displayInfo')}}"'>
                        @else
                        <img class="activator" src="{{URL::asset('/uploads/user_profile/'.$result[0]['user_profile'])}}"  style="width: 200px;height: 200px;"  onclick='javascrtpt:window.location.href="{{url('user/displayOthersInfo/'.$result[0]['user_id'])}}"'>
                        @endif
                    </div>
                    <div class="card-content">
                        <span class="card-title activator grey-text text-darken-4">{{$result[0]['user_name']}}</span>
                    </div>
                </div>
                <div class="switch" style="float: right;">
                <input type="hidden" value="{{$result[0]['user_id']}}" name="td-id">
                <label>
                    @if(session('user_id')==$result[0]['user_id'])

                    @elseif($friendship)
                        Unfollow
                        <input type="checkbox" class="fo_checkbox" checked="checked">
                        <span class="lever"></span>
                        Follow

                    @else
                        Unfollow
                        <input type="checkbox" class="unfo_checkbox"   >
                        <span class="lever"></span>
                        Follow

                    @endif

				</label>
			</div>
            </div>
        </fieldset>


    </span>
        @else
        <div style="margin-top: 150px;margin-left: 450px">
            <span style="color: #8D8D8D;font-size: 20px;">NO RESULT</span>


        </div>

@endif

    <script type="text/javascript">

/*这里对已关注的人做取消关注操作*/
        $(function(){
            layui.use('layer',function(){
                $('.switch').on('click','.fo_checkbox',function(){
                    var followed_id = $(this).parent().siblings([name='td-id']).val();
                    $.ajax({
                        url: '{{ url('user/follow') }}',
                        type:'get',
                        dataType: 'json',
                        data: {'type': 'unfollow','followed_id':followed_id,"_token":"{{csrf_token()}}"},
                    })
                        .done(function(data) {
                            layer.msg(data.msg,{
                                icon:data.icon,
                            });
                            if(data.icon==='1'){
                                setTimeout("refresh()()",750);
                            }
                        })
                        .fail(function() {
                            layer.msg('服务器wei响应!',{
                                icon:5
                            });
                        })
                        .always(function() {
                            console.log("complete");
                        });

                });

                $('.switch').on('click','.unfo_checkbox',function(){
                    var followed_id = $(this).parent().siblings([name='td-id']).val();
                    $.ajax({
                        url: '{{ url('user/follow') }}',
                        type:'get',
                        dataType: 'json',
                        data: {'type': 'follow','followed_id':followed_id,"_token":"{{csrf_token()}}"},
                    })
                        .done(function(data) {
                            layer.msg(data.msg,{
                                icon:data.icon,
                            });
                            if(data.icon==='1'){
                                setTimeout("refresh()",750);
                            }
                        })
                        .fail(function() {
                            layer.msg('服务器wei响应!',{
                                icon:5
                            });
                        })
                        .always(function() {
                            console.log("complete");
                        });

                });
            });
        });
        function refresh(){
          location.reload();
        }

/*

$(".search_btn").click(function(){
    var id= document.getElementById('search_user');
/!*    var id = document.getElementById('search_team');*!/
    if(id==null){
        setCookie('target','search_user');
        setCookie('placeholder','Search For User With ID......');
        setCookie('url',"{{ url('user/displaySearchResult') }}");
        location.reload(true);
    }
    else{
        setCookie('target','search_team');
        setCookie('placeholder','Search For Team With Team Name or Info......');
        setCookie('url',"{{ url('team/displaySearchResult')}}");
        location.reload(true);
    }
});

/!*对cookie的操作*!/
/!*修改cookie*!/
function setCookie(name,value)
{
    var Days = 30;
    var exp = new Date();
    exp.setTime(exp.getTime() + Days*24*60*60*1000);
    document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}

//读取cookies
function getCookie(name)
{
    var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");

    if(arr=document.cookie.match(reg))

        return unescape(arr[2]);
    else
        return null;
}
*/


    </script>

@endsection