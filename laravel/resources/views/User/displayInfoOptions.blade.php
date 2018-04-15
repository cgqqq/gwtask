@extends('User.displayInfo')
                @section('displayInfo_content')
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;width: 650px;color: #0C0C0C;font-weight: 600;">
                    <legend> {{trans('User/displayInfo.32')}}</legend>
                    @if(empty($privacies))
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        {{trans('User/displayInfo.33')}}

                        <label  style="float: right;margin-right: 50px;">
                            {{trans('User/displayInfo.34')}}
                            <input type="hidden" name="type" value="view_page">
                            <input type="checkbox" class="fo_checkbox" checked="unchecked">
                            <span class="lever"></span>
                            {{trans('User/displayInfo.35')}}
                        </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        {{trans('User/displayInfo.36')}}

                        <label  style="float: right;margin-right: 50px;">
                            {{trans('User/displayInfo.34')}}
                            <input type="hidden" name="type" value="download_resource">
                            <input type="checkbox" class="fo_checkbox" checked=true>
                            <span class="lever"></span>
                            {{trans('User/displayInfo.35')}}
                        </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        {{trans('User/displayInfo.37')}}

                    <label  style="float: right;margin-right: 50px;">
                        {{trans('User/displayInfo.34')}}
                            <input type="hidden" name="type" value="view_team_joined">
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            ON
                    </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        {{trans('User/displayInfo.38')}}

                        <label  style="float: right;margin-right: 50px;">
                            {{trans('User/displayInfo.34')}}
                            <input type="hidden" name="type" value="view_team_created">
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            {{trans('User/displayInfo.35')}}
                        </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        {{trans('User/displayInfo.39')}}

                        <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="hidden" name="type" value="view_task">
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            {{trans('User/displayInfo.35')}}
                        </label>
                    </div>
                        @else
                        <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                            {{trans('User/displayInfo.33')}}

                            <label  style="float: right;margin-right: 50px;">
                                {{trans('User/displayInfo.34')}}
                                <input type="hidden" name="type" value="view_page">
                                @if($privacies['view_page']=='0')
                                <input type="checkbox" class="fo_checkbox" checked="checked">
                                @else
                                <input type="checkbox" class="fo_checkbox">
                                @endif
                                <span class="lever"></span>
                                {{trans('User/displayInfo.35')}}
                            </label>
                        </div>
                        <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                            {{trans('User/displayInfo.36')}}

                            <label  style="float: right;margin-right: 50px;">
                                {{trans('User/displayInfo.34')}}
                                <input type="hidden" name="type" value="download_resource">
                                @if($privacies['view_page']=='0')
                                    <input type="checkbox" class="fo_checkbox" checked="checked">
                                @else
                                    <input type="checkbox" class="fo_checkbox">
                                @endif
                                <span class="lever"></span>
                                {{trans('User/displayInfo.35')}}
                            </label>
                        </div>
                        <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                            {{trans('User/displayInfo.37')}}

                            <label  style="float: right;margin-right: 50px;">
                                {{trans('User/displayInfo.34')}}
                                <input type="hidden" name="type" value="view_team_joined">
                                @if($privacies['view_page']=='0')
                                    <input type="checkbox" class="fo_checkbox" checked="checked">
                                @else
                                    <input type="checkbox" class="fo_checkbox">
                                @endif
                                <span class="lever"></span>
                                {{trans('User/displayInfo.35')}}
                            </label>
                        </div>
                        <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                            {{trans('User/displayInfo.38')}}

                            <label  style="float: right;margin-right: 50px;">
                                {{trans('User/displayInfo.34')}}
                                <input type="hidden" name="type" value="view_team_created">
                                @if($privacies['view_page']=='0')
                                    <input type="checkbox" class="fo_checkbox" checked="checked">
                                @else
                                    <input type="checkbox" class="fo_checkbox">
                                @endif
                                <span class="lever"></span>
                                {{trans('User/displayInfo.35')}}
                            </label>
                        </div>
                        <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                            {{trans('User/displayInfo.39')}}

                            <label  style="float: right;margin-right: 50px;">
                                {{trans('User/displayInfo.34')}}
                                <input type="hidden" name="type" value="view_task">
                                @if($privacies['view_page']=='0')
                                    <input type="checkbox" class="fo_checkbox" checked="checked">
                                @else
                                    <input type="checkbox" class="fo_checkbox">
                                @endif
                                <span class="lever"></span>
                                {{trans('User/displayInfo.35')}}
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
