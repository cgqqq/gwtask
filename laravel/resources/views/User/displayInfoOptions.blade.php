@extends('User.displayInfo')
                @section('displayInfo_content')
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;width: 650px;color: #0C0C0C;font-weight: 600;">
                    <legend>Privacy</legend>
                    @if(empty($privacies))
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        Allow others to view my personal updatings.

                        <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="hidden" name="type" value="view_page">
                            <input type="checkbox" class="fo_checkbox" checked="unchecked">
                            <span class="lever"></span>
                            ON
                        </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        Allow others to download my resources.

                        <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="hidden" name="type" value="download_resource">
                            <input type="checkbox" class="fo_checkbox" checked=true>
                            <span class="lever"></span>
                            ON
                        </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                    Allow others to view the teams I have joined.

                    <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="hidden" name="type" value="view_team_joined">
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            ON
                    </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        Allow others to view the teams I have created.

                        <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="hidden" name="type" value="view_team_created">
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            ON
                        </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        Allow others to view the My tasks.

                        <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="hidden" name="type" value="view_task">
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            ON
                        </label>
                    </div>
                        @else
                        <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                            Allow others to view my personal updatings.

                            <label  style="float: right;margin-right: 50px;">
                                OFF
                                <input type="hidden" name="type" value="view_page">
                                @if($privacies['view_page']=='0')
                                <input type="checkbox" class="fo_checkbox" checked="checked">
                                @else
                                <input type="checkbox" class="fo_checkbox">
                                @endif
                                <span class="lever"></span>
                                ON
                            </label>
                        </div>
                        <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                            Allow others to download my resources.

                            <label  style="float: right;margin-right: 50px;">
                                OFF
                                <input type="hidden" name="type" value="download_resource">
                                @if($privacies['view_page']=='0')
                                    <input type="checkbox" class="fo_checkbox" checked="checked">
                                @else
                                    <input type="checkbox" class="fo_checkbox">
                                @endif
                                <span class="lever"></span>
                                ON
                            </label>
                        </div>
                        <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                            Allow others to view the teams I have joined.

                            <label  style="float: right;margin-right: 50px;">
                                OFF
                                <input type="hidden" name="type" value="view_team_joined">
                                @if($privacies['view_page']=='0')
                                    <input type="checkbox" class="fo_checkbox" checked="checked">
                                @else
                                    <input type="checkbox" class="fo_checkbox">
                                @endif
                                <span class="lever"></span>
                                ON
                            </label>
                        </div>
                        <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                            Allow others to view the teams I have created.

                            <label  style="float: right;margin-right: 50px;">
                                OFF
                                <input type="hidden" name="type" value="view_team_created">
                                @if($privacies['view_page']=='0')
                                    <input type="checkbox" class="fo_checkbox" checked="checked">
                                @else
                                    <input type="checkbox" class="fo_checkbox">
                                @endif
                                <span class="lever"></span>
                                ON
                            </label>
                        </div>
                        <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                            Allow others to view the My tasks.

                            <label  style="float: right;margin-right: 50px;">
                                OFF
                                <input type="hidden" name="type" value="view_task">
                                @if($privacies['view_page']=='0')
                                    <input type="checkbox" class="fo_checkbox" checked="checked">
                                @else
                                    <input type="checkbox" class="fo_checkbox">
                                @endif
                                <span class="lever"></span>
                                ON
                            </label>
                        </div>
                    @endif
                </fieldset>
                    <script>
                        $(function(){
                            layui.use('layer',function(){
                                $('.switch').on('click','.fo_checkbox',function(){
                                    var type= $(this).siblings([name='type']).val();
                                    $.ajax({
                                        url: '{{ url('user/setPrivacy') }}',
                                        type:'post',
                                        dataType: 'json',
                                        data: {'type': type,"_token":"{{csrf_token()}}"},
                                    })
                                        .done(function(data) {
                                            layer.msg(data.msg)
                                        })
                                        .fail(function() {
                                            layer.msg('Busy Network!',{
                                                icon:5
                                            });
                                        })
                                        .always(function() {
                                            console.log("complete");
                                        });

                                });
                            });
                        });
                    </script>
                @endsection
