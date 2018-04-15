@extends('User.displayInfo')


@section('displayInfo_content')
    <div style="width: 680px;height: 40px;" id="profile"  class="newA" >
        <a href="{{url('user/displayInfoMailBox')}}">
        <img class="layui-circle" style="border: 1px solid black;margin-left: 20px;margin-top: 10px" src="{{URL::asset('/images/back.png')}}" >
        </a>
    </div>
    <div style="height: 460px;width: 680px;padding: 30px">
        <div id="mail_content" style="color: black;font-size: 15px;width: 620px;height: 375px;font-weight: 600;border: 2px dotted black">
            <div style="margin: 16px;">
            <span style="color: #8D8D8D">
             {{trans('User/displayInfo.27')}}
            </span>{{$mail_from_info['user_name']}}</div>
            <div style="margin-left: 16px;margin-top: 10px;width: 580px;height: 30px;" class="scroll"><span style="color:#8D8D8D;">{{trans('User/displayInfo.28')}}</span>{{$mail_content['mail_title']}}</div>
            <div style="margin-left:16px;margin-top: 10px;width: 580px;height: 280px; word-wrap:break-word;" class="scroll"><span style="color:#8D8D8D;">{{trans('User/displayInfo.29')}}</span>{{$mail_content['mail_content']}}</div>
        </div>
        @if($mail_content['mail_type']=="1")
        <div style="width: 130px;height: 25px;margin-top: 0px;font-size: 16px;font-weight: 600;color: black;float: right;margin-top: 10px" class="newA">
            <a class="quick-reply" >
                {{trans('User/displayInfo.30')}}<img style="float:right;margin-right: 10px" src="{{URL::asset('/images/reply.png')}}" ></a>
            <input type="hidden" name="mail_to_id" value="{{$mail_content['mail_from_id']}}">
        </div>
            @else
        @endif
    </div>
    <script>
        $(function() {
            layui.use('layer', function () {
                $('.newA').on('click','.quick-reply',function(){
                    var mail_to_id = $(this).siblings("[name='mail_to_id']").val();
                    var mail_from_id="{{session('user_id')}}";
                    layer.prompt({
                        title: 'Please Input Mail Subject!',
                        btn:['Next','Cancle'],
                        formType: 0,
                    }, function(text1,index){

                        layer.close(index);
                        layer.prompt({
                            title: 'Mail Content',
                            btn:['Confirm','Cancle'],
                            closeBtn:1,
                            formType: 2
                        }, function(text2,index){
                            var title=text1;
                            var content=text2;
                            $.ajax({
                                url: "{{ url('user/sendMail') }}",
                                type: 'post',
                                dataType: 'json',
                                data: {'mail_to_id': mail_to_id,'mail_from_id':mail_from_id,'mail_title':title,'mail_content':content,"_token":"{{csrf_token()}}"}
                            })
                                .done(function(data) {
                                    layer.msg(data.msg);
                                    location.reload(true);
                                })
                                .fail(function() {
                                    layer.msg(data.msg);
                                })
                                .always(function() {
                                    console.log("complete");
                                });
                            layer.close(index);
                        });
                    });

                });
            });
        });

    </script>

@endsection