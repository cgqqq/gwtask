@extends('layouts.home')


@section('content')
    <div style="width: 1030px;height: 800px">
        <div style="background-color: #4aaf51;width: 1030px;height: 300px;float: left;color: #0C0C0C" class="shadow">
            <img class="layui-circle" style="height: 150px;width:150px;margin-top: 5%;margin-left:43%" src="{{ asset(session('user_profile')) }}" >
            <p style=" background-color:#fcfcfc;margin-top: 10px;font-weight: 800;font-size: 16px;text-align:center; width:1030px;height:33px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;  ">
                    {{ session('user_name')}}
            </p>

        </div>
        <div style="width: 1030px;height: 500px;float: left">
            <div style="background-color: #0C0C0C;width: 350px;height: 500px;float: left">
                <div class="collection" style="margin-top: 20px;width: 350px;padding: 5px;">
                    <a href="{{url('user/displayInfo')}}" class="collection-item" style="font-size: 20px;line-height: 30px;">
                        <img class="layui-circle" style="height: 35px;width:35px;" src="{{URL::asset('/images/personal_info.png')}}" >
                        Pesonal Information
                    </a>
                    <a href="{{url('user/displayInfoOptions')}}" class="collection-item" style="font-size: 20px;line-height: 30px">
                        <img class="layui-circle" style="height: 35px;width:35px;" src="{{URL::asset('/images/option.png')}}" >
                        Options
                    </a>
                    <a href="" class="collection-item" style="font-size: 20px;line-height: 30px">
                        <img class="layui-circle" style="height: 35px;width:35px;" src="{{URL::asset('/images/mail_box.png')}}" >
                        Mail Box
                    </a>

                </div>
            </div>
            <div style="width: 680px;height: 500px;float: left">
                <fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;width: 650px;color: #0C0C0C;font-weight: 600;">
                    <legend>Privacy</legend>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        Allow others to download my resources.

                        <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            ON
                        </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                    Allow others to view the teams I have joined.

                    <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            ON
                    </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        Allow others to view the teams I have created.

                        <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            ON
                        </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                        Allow others to view the My tasks.

                        <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            ON
                        </label>
                    </div>
                    <div class="switch" style="margin-left: 30px;float: left;margin-top: 40px;width: 620px;">
                       Clear cookies.

                        <label  style="float: right;margin-right: 50px;">
                            OFF
                            <input type="checkbox" class="fo_checkbox" checked="checked">
                            <span class="lever"></span>
                            ON
                        </label>
                    </div>
                </fieldset>

            </div>
        </div>

    </div>
@endsection