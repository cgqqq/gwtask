@extends('User.displayInfo')
                @section('displayInfo_content')
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
                @endsection
