@extends('User.displayInfo')


@section('displayInfo_content')
    <div class="layui-tab" >
        <ul class="layui-tab-title">
            <li  class="layui-this"  style="background-color: #4aaf51;"><img src="{{URL::asset('/images/read_mails.png')}}"></li>
            <li style="background-color: #4aaf51;"><img  src="{{URL::asset('/images/write_mails.png')}}"></li>
            <li style="background-color: #4aaf51; " id="tab3" ><img  src="{{URL::asset('/images/sent_mails.png')}}"></li>
        </ul>
        <div class="layui-tab-content" style="color: #0C0C0C">
            <div class="layui-tab-item layui-show" >
                <div style="width: 680px;height: 440px;color: black;font-size: 15px;" class="scroll">
                    <div style="float: left;">
                        @if(empty($invitations))
                        @else
                            @foreach($invitations as $invitation)
                        <fieldset class="layui-elem-field layui-field-title" style="width: 650px;color: #0C0C0C;font-weight: 600;">
                            <legend>Invitation({{($invitationNum)}})</legend>
                        <div class="card" >
                            <div class="card-content" style="margin-left:10px;padding:5px;width: 600px;height: 80px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;border: 2px solid #0C0C0C">
                              <span STYLE="font-size: 20px;font-weight: 600;">
                                <img src="{{URL::asset('/images/invitation.png')}}">
                                  You got an Team Invitation from {{$invitation['invitation_team_name']}}
                            </span>
                                <button class="layui-btn view_invite" style="background-color: #fcfcfc;float: right;color: #0C0C0C;border: solid 2px black;">view</button>
                                <input type="hidden" name="team_id" value="{{$invitation['team_id']}}">
                                <input type="hidden" name="invitation_title" value="{{$invitation['title']}}">
                                <input type="hidden" name="invitation_content" value="{{$invitation['content']}}">
                            </div>
                        </div>
                        </fieldset>
                            @endforeach
                        @endif
                        <fieldset class="layui-elem-field layui-field-title" style="width: 650px;color: #0C0C0C;font-weight: 600;">
                            <legend>Mails({{$mailsNum}})</legend>
                            @if($newsNum!=0)
                            @foreach($mailsRecieved as $mail)
                                @if($mail['mail_status']=="1")
                            <div class="card" >
                                <div class="card-content" style="margin-left:10px;padding:5px;width: 600px;height: 100px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;border: 2px solid #0C0C0C">

                                    <div style="margin-bottom: 75px;float: right">
                                        <img src="{{URL::asset('/images/dump.png')}}" class="dump-mail">
                                    </div>
                                    <input type="hidden" name="mail_id" value="{{$mail['mail_id']}}">
                                    <span STYLE="font-size: 20px;font-weight: 600;">
                                        @if($mail['mail_type']=='1')
                                <img src="{{URL::asset('/images/read.png')}}">
                                        @else
                                <img src="{{URL::asset('/images/alert.png')}}">
                                        @endif
                                  You got a mail from {{$mail['mail_from_name']}}
                                  <p style="color: #8D8D8D;font-size: 12px;width: 150px;">
                                      {{$mail['mail_time_gap']}}
                                  </p>
                            </span>

                                    <button class="layui-btn" style="background-color: #fcfcfc;float: right;color: #0C0C0C;border: solid 2px black;"   onclick='javascrtpt:window.location.href="{{url('user/displayInfoMailContent/'.$mail['mail_id'])}}"'>view</button>
                                </div>
                            </div>
                            @else
                            <div class="card" >
                                <div class="card-content" style="margin-left:10px;padding:5px;width: 600px;height: 100px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;border: 2px solid #0C0C0C">
                                    <div style="margin-bottom: 75px;float: right">
                                        <img src="{{URL::asset('/images/dump.png')}}" class="dump-mail">
                                    </div>
                                    <input type="hidden" name="mail_id" value="{{$mail['mail_id']}}">
                              <span STYLE="font-size: 20px;font-weight: 600;">
                                @if($mail['mail_type']=='1')
                                      <img src="{{URL::asset('/images/unread.png')}}">
                                  @else
                                      <img src="{{URL::asset('/images/alert_unread.png')}}">
                                  @endif
                                   You got a mail from {{$mail['mail_from_name']}}
                                  <p style="color: #8D8D8D;font-size: 12px;width: 150px;">
                                      {{$mail['mail_time_gap']}}
                                  </p>
                            </span>
                                    <button class="layui-btn" style="background-color: #fcfcfc;float: right;color: #0C0C0C;border: solid 2px black;" onclick='javascrtpt:window.location.href="{{url('user/displayInfoMailContent/'.$mail['mail_id'])}}"'>view</button>

                                </div>
                            </div>
                            @endif
                            @endforeach

                        </fieldset>
                        @else
                            <p style="color: #0C0C0C;font-size: 20px;font-weight: 900;margin-left: 200px;margin-top: 100px">
                                No Mail In Your Mail Box!</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="layui-tab-item" >
                <form class="form-horizontal"  style="color: #333333;font-size: large;margin-top: 30px;">
                    {{ csrf_field() }}

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="col-md-4 control-label" >Sent to</label>

                        <div class="col-md-6">
                            <input type="text" id="mail_to_id" required="" class="form-control" placeholder="Please Input User's ID......">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 20px;">
                        <label class="col-md-4 control-label" >Subject</label>

                        <div class="col-md-6">
                            <input type="text" id="mail_title" required="" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 40px;">
                        <label class="col-md-4 control-label">Content</label>

                        <div class="col-md-6">
                            <textarea rows="4" cols="50" class="form-control scroll" style="max-height: 280px;max-width: 320px" id="mail_content"></textarea>

                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-4">
                        <button id="submit" type="submit" class="layui-btn shadow" style="border: 2px solid #0C0C0C;color: #0C0C0C">
                            Send
                        </button>
                    </div>
                    <input type="hidden" name='mail_from_id' value="{{session('user_id')}}">
                </form>
            </div>
            <div class="layui-tab-item" >
                <div style="width: 680px;height: 440px;color: black;font-size: 15px;" class="scroll">
                    <div style="float: left;">
                @if(!empty($sentMails))
                @foreach($sentMails as $mail)
                <div class="card" >
                    <div class="card-content" style="margin-left:10px;padding:5px;width: 600px;height: 100px;white-space: nowrap;overflow: hidden;text-overflow: ellipsis;border: 2px solid #0C0C0C">
                        <div style="margin-bottom: 75px;float: right">
                            <img src="{{URL::asset('/images/dump.png')}}" class="dump-mail">
                        </div>
                        <input type="hidden" name="mail_id" value="{{$mail['mail_id']}}">
                        <span STYLE="font-size: 20px;font-weight: 600;">
                                <img src="{{URL::asset('/images/sent.png')}}">
                                   You have sent a mail to {{$mail['mail_to_name']}}
                            <p style="color: #8D8D8D;font-size: 12px;width: 150px;">
                                      {{$mail['mail_time_gap']}}
                                  </p>
                            </span>
                        <button class="layui-btn" style="background-color: #fcfcfc;float: right;color: #0C0C0C;border: solid 2px black;" onclick='javascrtpt:window.location.href="{{url('user/displayInfoMyMailContent/'.$mail['mail_id'])}}"'>view</button>

                    </div>
                </div>
                    @endforeach
                    @else
                            <p style="color: #0C0C0C;font-size: 20px;font-weight: 900;margin-left: 180px;margin-top: 160px">
                                  You haven't send any mail yet!
                            </p>
                    @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(function(){
            layui.use('layer',function(){
                $('.card').on('click','.view_invite',function(){
                    //配置一个透明的询问框
                    var invitation_title=$(this).siblings("[name='invitation_title']").val();
                    var invitation_content=$(this).siblings("[name='invitation_content']").val();
                    var team_id = $(this).siblings("[name='team_id']").val();
                    layer.open({
                        type: 1
                        ,title: false //不显示标题栏
                        ,area: '300px;'
                        ,shade: 0.8
                        ,id: 'LAY_layuipro' //设定一个id，防止重复弹出
                        ,resize: false
                        ,closeBtn:true
                        ,btn: ['Accept', 'Refuse']
                        ,btnAlign: 'c'
                        ,skin:'a-class'
                        ,moveType: 1 //拖拽模式，0或者1
                        ,content: '<div class="scroll" style="padding: 20px; line-height: 22px;color: #0C0C0C; font-weight: 300;height: 200px;width: 300px;word-wrap: break-word;font-size: 15px;font-weight: 700;color: #0C0C0C"><span style="font-size: 15px;font-weight: 700;color: #8D8D8D">Title : </span>'+invitation_title+'<br><span style="font-size: 15px;font-weight: 700;color: #8D8D8D">Content : </span>'+invitation_content+'</div>'
                        ,btn1: function(index){
                            $.ajax({
                                url: "{{ url('user/acceptInvitation') }}",
                                type: 'post',
                                dataType:'json',
                                data: {'user_id': "{{ session('user_id') }}",'team_id':team_id,"_token":"{{csrf_token()}}"}
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
                        }
                        ,btn2:function(layero){
                            $.ajax({
                                url: "{{ url('user/refuseInvitation') }}",
                                type: 'post',
                                dataType:'json',
                                data: {'user_id': "{{ session('user_id') }}",'team_id':team_id,"_token":"{{csrf_token()}}"},
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
                        }
                    });


                });
            });
        });

        $(function(){
            layui.use('layer',function(){
                $('.form-horizontal').on('click','#submit',function(event){
                    event.preventDefault();
                    var mail_to_id = $('#mail_to_id').val();
                    var mail_from_id= $(this).parent().siblings("[name='mail_from_id']").val();
                    var mail_title=$('#mail_title').val();
                    var mail_content=$('#mail_content').val();
                    if(mail_to_id!=null&mail_to_id!=''){
                        $.ajax({
                            url: "{{ url('user/sendMail') }}",
                            type: 'post',
                            dataType: 'json',
                            skin:'a-class',
                            data: {"mail_to_id": mail_to_id,"mail_from_id":mail_from_id,"mail_title": mail_title,"mail_content":mail_content,"_token":"{{csrf_token()}}"}
                        })
                            .done(function(data) {
                                layer.msg(data.msg);
                                location.reload(true);
                            })
                            .fail(function(data) {
                                layer.msg(data.msg);
                            })
                            .always(function() {
                                console.log("complete");
                            });
                    }else{
                        layer.msg('Please input the id of mail receiver');
                    }
                });
            });
        });

        $(function(){
            layui.use('layer',function(){
                $('.card').on('click','.dump-mail',function(){
                    //配置一个透明的询问框
                    var mail_id = $(this).parent().siblings("[name='mail_id']").val();
                    $.ajax({
                        url: "{{ url('user/dumpMail') }}",
                        type: 'post',
                        dataType:'json',
                        data: {'mail_id': mail_id,"_token":"{{csrf_token()}}"}
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

                });


                });
        });


    </script>
@endsection