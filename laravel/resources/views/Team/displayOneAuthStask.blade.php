{{--队长查看任务页面--}}
@extends('team.'.$AuthOrNot)
@section('team_content')
    <div style="min-height: 200px;height: auto;word-wrap:break-word;">
    <p style="font-size: 25px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 25px;color: #8D8D8D">Sub Task : </span>{{$stask_info['stask_name']}}</p>
    <p style="font-size: 15px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%"><span style="font-size: 18px;color: #8D8D8D">Detail : </span>{{$stask_info['stask_description']}}</p>
    </div>
    {{--<form class="form-group shadow"  style="color: #333333;font-size: large;padding-top: 20px;border: 5px solid black;width: 90%;margin: 10px" >
        {{ csrf_field() }}
        <input type="hidden" name="" id="team_id" value="{{$stask_info['stask_id']}}">
        <div class="form-group" style="margin-bottom: 20px;">
            <label for="stask_result_descri" class="col-md-4 control-label">Brief Description</label>

            <div class="col-md-6"  >
                <textarea rows="2" cols="25" class="form-control"  style="min-width:300px;max-width: 300px;width: auto"></textarea>

            </div>
        </div>
        <div class="form-group " style="text-align: center;margin-right: 65px">

            <button type="submit" class="layui-btn" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fcfcfc" >
                Submit
            </button>
            <div style="width:600px;height:60px;margin-left:308px;margin-top: 20px;color: black;" class="user_box scroll"  >

            </div>
        </div>
    </form>--}}
    <form class="form-group shadow" enctype="multipart/form-data" style="color: #333333;font-size: large;padding-top: 20px;border: 5px solid black;width: 90%;margin: 10px;line-height:20px;float:left;color: #0C0C0C;cursor: hand;">
        {{ csrf_field() }}
        <div class="form-group" style="margin-bottom: 10px;" >
            <div class="col-md-6">
                <label for="stask_result_descri" class="control-label">Brief Description</label>
                <textarea type="text" name="content" id="content" class="form-control" style="width: auto;height: 100px;max-height: 100px;max-width: 200px;min-width: 200px" required></textarea>
            </div>
        </div>

        <input type="hidden" name="flag" value='1' id="flag">
        <input type="hidden" name="updater_id" value="{{session('user_id')}}" >
    </form>
    <div class="form-group" STYLE="width:100px">
                <span class="btn btn-success fileinput-button" style="background-color: #3F3F3F;margin-top: 5px;float: right;margin-right: 540px;border: solid 1px transparent" >
                    <span style="font-weight: 700;color: #0C0C0C;font-size: 15px">Upload</span>
                    <input type="file" id="resource" class="" name="resource"  style="opacity: 0;">
                </span>
    </div>
    <script type="text/javascript">

        function isHidden(oDiv){
            var vDiv = document.getElementById(oDiv);
            vDiv.style.display = (vDiv.style.display == 'none')?'block':'none';
        }
        $(function(){
            layui.use('layer', function(){
                $('.box').on('click','.delete_tran',function(event){
                    event.preventDefault();
                    var id = $(this).parent().siblings("[name='tran_id']").val();
                    $.ajax({
                        url: "{{ url('task/deleteTransaction') }}",
                        type: 'post',
                        dataType: 'json',
                        data: {"tran_id":id,"_token":"{{csrf_token()}}"}
                    })
                        .done(function(data) {
                            layer.msg(data.msg);
                        })
                        .fail(function(data) {
                            layer.msg(data.msg);
                        })
                        .always(function() {
                            console.log("complete");
                        });
                    event.preventDefault();
                    $(this).parent().parent().parent().remove();
                });
            });
        });
    </script>
    {{ $paged->links() }}
@endsection