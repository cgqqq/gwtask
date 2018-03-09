@extends('layouts.home')

@section('content')

	<script type="text/javascript">
        $(function(){
            layui.use('layer',function(){
                //添加单个队员
                $('.layui-table').on('click','.add_one',function(){
                    var team_id = $(this).parent().siblings("[name='team_id']").val();
                    var user_list = [];
                    user_list.push($(this).parent().prev().prev().children('.user_id').text());
                    console.log('队ID：'+team_id+' 要增加的队员：'+user_list)
                    $.ajax({
                        url: "{{ url('team/addTeammates') }}",
                        type: 'post',
                        dataType: 'json',
                        data: {'team_id':team_id,'user_list':user_list},
                    })
                        .done(function(data) {
                            layer.msg(data.msg,{
                                icon:data.icon
                            });
                            if(data.icon==2||data.icon==3){
                                console.log('添加组员失败列表：'+data.fail_list)
                            }
                            if(data.icon==1){
                                setTimeout('location.reload()',1500);
                            }
                        })
                        .fail(function() {
                            console.log("error");
                        })
                        .always(function() {
                            console.log("complete");
                        });
                });
                //批量添加操作
                $('.layui-table').on('click','.btn',function(){
                    var team_name = $('.team_name').text();
                    var user_list = [];
                    $('.checkOne:checked').each(
                        function(){
                            user_list.push($(this).next().text());
                        }
                    );
                    console.log(user_list);
                });
            });
            //全选or全不选
            $('#checkAll').change(function(){
                $('.checkOne').prop('checked', $(this).prop('checked'))
            });
        });
	</script>

	<table class="layui-table" style="width: 900px;">
		<div style="color: black;" id="team_name">{{ $teamInfo[0]['team_name'] }}</div>
		<br><div style="color: black;" id="team_name">用户列表：</div>
		<thead>
		<tr>
			<td>
				<input type="checkbox" id='checkAll' style="position: absolute;top: 12px;"><label class="user_id" for="checkAll" style="position: absolute;top: 15px;left: 35px;cursor: pointer;">用户ID</label>
			</td>
			<td>
				姓名
			</td>
			<td colspan="4">
				操作
			</td>
		</tr>
		</thead>
		@foreach($users as $user)
			<tr>
				<td class="td-id">
					<input type='checkbox' class='checkOne' style='display:inline-block;position :absolute ; top:12px;'>"
					<div class="user_id" style="position: absolute;top: 15px;left: 35px;display: inline-block;">{{ $user['user_id'] }}</div>

				</td>
				<td class="user_name">
					{{ $user['user_name'] }}
				</td>
				<td>
					<button class="add_one" >增加</button>
				</td>
				<input type="hidden" name="team_id" value="{{$teamInfo[0]['team_id']}}">
				</td>
			</tr>
		@endforeach
	</table>
	{{ $paged->links() }}
@endsection