{{--子任务增加及分配页面--}}
@extends('layouts.home')

@section('content')
<script type="text/javascript" src="{{ asset('js/json2.js') }}"></script>
<script type='text/javascript'>
	$(function(){
		layui.use('layer',function(){
			var $ = layui.jquery, layer = layui.layer;
			//初始化团队成员列表变量
			var team_user_list = null ;
			//初始化加载团队成员列表
            $('#has-get-users').val('0');
			if($('#has-get-users').val()=='0'){
				$.ajax({
					url: "{{url('task/getTeamUsers')}}",
					type: 'get',
					dataType: 'json',
					data: {'team_id':$('#team_id').val(),"_token":"{{csrf_token()}}"},
				})
				.done(function(data) {
					//将后台返回的该团队所有成员json对象转json字符串
					tmp = JSON.stringify(data.team_user_list);
					console.log(tmp);
					//json字符串转数组
					team_user_list = JSON.parse(tmp);
					for(var i in team_user_list){
						//为每一个组员初始化变量，判断是否被选
						team_user_list[i].push({'isSelected':'0'});
						console.log(i+':'+team_user_list[i][1].user_name+','+team_user_list[i][0].user_id+','+team_user_list[i][2].isSelected);

					}
					// console.log(team_user_list[1][1].user_name);
					console.log(team_user_list);
					$('#has-get-users').val('1');
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
				
			}
			//新增子任务
            $('.addSTask').on('click',function(){
                var new_div=$('#0').clone();
                $('#1').each(function(){
                    $('#1').attr("class",'stask_form');
				});
                $('#stask_box').append(new_div);

            });
			//删除子任务
			$('#stask_box').on('click','.del',function(event){
				event.preventDefault();
               $(this).parent().find('.user_box').find('.close').each(function () {
                   var id=$(this).attr('id');
                    for(var i in team_user_list) {
                        if (id == team_user_list[i][0].user_id) {
                            team_user_list[i][2] = {'isSelected': '0'};
                        }
                    }
                });

				$(this).parent().parent().remove();
			});
            //选择组员
			$('#stask_box').on('click','.choose',function(event){
				//要显示选中组员的地方
				var parent_user_box = $(this).next();
				event.preventDefault();
				allocate_user = [];
				layer.open({
						type: 1,
						skin: 'layui-layer-rim', //加上边框
						area: ['550px', '500px'], //宽高
						title:"Choose Members In Charge",
						content:'<table class="layui-table team_user_item" ><tr><td><label class="layui-form-label" style="padding:0;font-weight:700;font-size: 14px;color: #0C0C0C">UserList</label></td><td><button class="layui-btn layui-btn-primary confirm" style="border: solid 2px black;background-color: white;color:#0C0C0C;font-weight:700;font-size: 14px;">Confirm</button></td></tr></table>'
				});	
				var user_id_head = "<input type='hidden' name='' class='user_id' value='";
				var user_id_tail = "'>";
				var checkBox = "<input name='' lay-skin='primary' type='checkbox' style='position: relative;top:5px;margin-right: 200px;float: right'>";
				//显示所有可选组员
				for(var i in team_user_list){
						// console.log(team_user_list[i]);
						if(team_user_list[i][2].isSelected!='1'){
							$('.team_user_item').append("<tr><td style='position:relative;left:5px;' colspan='2'>"+user_id_head+team_user_list[i][0].user_id+user_id_tail+"<b class='user_name_item'>"+team_user_list[i][1].user_name+"</b>"+checkBox+"</td></tr>");
						}
				}
				//确认选中的队员			
				$('.confirm').on('click',{'parent_user_box':parent_user_box},function(event){
					//传过来的子任务界面要显示选中组员的地方
					var parent_user_box = event.data.parent_user_box;
					//选中的组员列表
					$('input:checked').each(function(){
						var selected_user_id = $(this).prev().prev().val();
						var selected_user = $(this).prev().html();
						//所选中组员的user_id和user_name
						var selected_user_info = {
							'selected_user_id':selected_user_id,
							'selected_user':selected_user
						};
						allocate_user.push(selected_user_info);
						for(var i in team_user_list){
							//若未被选中组员与当前
							if(selected_user.trim()==team_user_list[i][1].user_name){
								team_user_list[i][2] = {'isSelected':'1'};
							}
						}
					});
					console.log('选中列表:'+JSON.stringify(allocate_user));
					console.log('选完用户后：'+JSON.stringify(team_user_list));
					//关闭选择子任务成员界面
					layer.closeAll();		
					//对指定子任务显示显示所选组员姓名及传user_id
					for(var i in allocate_user){
						// alert(allocate_user[i]);
                        var delete_img="{{URL::asset('/images/delete2.png')}}";
                        var new_chip='<div class="chip" id="chip_clone" style="height: 35px;">'+
                            allocate_user[i].selected_user+
                            '<i class="close" id='+allocate_user[i].selected_user_id+
							' ><img src='+
                            delete_img+
                            ' ></i>' +
                            '<input type="hidden" name="" class="selected_user_id" value="'+allocate_user[i].selected_user_id+'">'+
                            '</div>';
                        parent_user_box.append(new_chip);
					}					
				});
			});
            //删除已选
            $('#stask_box').on('click','.close',function(event){
                event.preventDefault();
                var id=$(this).attr('id');
                for(var i in team_user_list){
                    //若未被选中组员与当前
                    for(var i in team_user_list) {
                        if (id == team_user_list[i][0].user_id) {
                            team_user_list[i][2] = {'isSelected': '0'};
                        }
                    }
                }
                $(this).parent().remove();
            });
			//发布子任务
			$('#submit').on('click',function(){
				var sub_task_all = [];
				$('.stask_form').each(function(){
                        //子任务项
                        var sub_task_item = [];
                        //子任务成员user_id
                        var sub_task_users = [];
                        //子任务名
                        var sub_task_name = $(this).find('.sub_task_name').val();
                        //子任务描述
                        var sub_task_descri = $(this).find('.sub_task_descri').val();
                        $(this).find('.user_box').find('.selected_user_id').each(function () {
                            sub_task_users.push($(this).val());
                        });
                        // sub_task_item['sub_task_name'] = sub_task_name;
                        // sub_task_item['sub_task_descri'] = sub_task_descri;
                        // sub_task_item['sub_task_users'] = sub_task_users;
						if(sub_task_name==''||sub_task_name==null){
						}else{
                        sub_task_item = {
                            'sub_task_name': sub_task_name,
                            'sub_task_descri': sub_task_descri,
                            'sub_task_users': sub_task_users
                        };
                        sub_task_all.push(sub_task_item);}

				});
				console.log('传给后台的子任务信息及对应成员:'+sub_task_all);
				$.ajax({
					url: "{{ url('task/allocateSub') }}",
					type: 'post',
					dataType: 'json',
					data: {'task_id':$('#task_id').val(),'sub_task_all':sub_task_all,"_token":"{{csrf_token()}}"},
				})
				.done(function(data) {
                    	var return_team_id = data.team_id;
						layer.msg(data.msg);
						var url="{{url('task/displayAll')}}";
                    	setTimeout("location.href='"+url+"'",1500);
				})
				.fail(function() {
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});

			});

		});
	});
	function objToArray(array) {
    var arr = []
    for (var i in array) {
        arr.push(array[i]); 
    }
    console.log(arr);
    return arr;
}
</script>
<div style="width: 1030px;height: 150px;">
	<p style="font-size: 40px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #0C0C0C;padding: 10px;width: 100%">{{trans('Task/displayAllocateSubTask.3')}}{{$return_data['team_name']}}</p>
	<p style="font-size: 25px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #3F3F3F;padding: 10px;width: 100%">{{trans('Task/displayAllocateSubTask.4')}}{{$return_data['task_name']}}</p>
	<div style="float: right;margin-right: 30px;">
		<button type="submit" class="layui-btn shadow addSTask" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fff200" >
			{{trans('Task/displayAllocateSubTask.5')}}
		</button>
	</div>
</div>
<form class="form-horizontal"  style="color: #333333;font-size: large;padding-top: 20px" >
	{{ csrf_field() }}
	<input type="hidden" name="" id="team_id" value="{{$return_data['team_id']}}">
	<input type="hidden" name="" id="has-get-users" value="0">
	<div style="width: 1030px;padding-top:50px;height: auto;min-height: 550px;padding-bottom: 40px"id="stask_box" class="scroll">
		<div style="width: 1030px;height:300px;float: left;margin-top: 20px;" class="stask_form" id="1" >
			<div style="width:900px;height: 300px; margin-left: auto;margin-right: auto;border: 3px solid black;" >
				<div style="width: 850px;height:180px;float: left;margin-top: 20px">
					<div class="form-group" style="margin-bottom: 30px;">
						<label for="stask_name" class="col-md-4 control-label" >{{trans('Task/displayAllocateSubTask.6')}}</label>

						<div class="col-md-6">
							<input type="text" required="" class="form-control sub_task_name" >
						</div>
					</div>
					<div class="form-group" style="margin-bottom: 20px;">
						<label for="stask_descri" class="col-md-4 control-label">{{trans('Task/displayAllocateSubTask.7')}}</label>

						<div class="col-md-6"  >
							<textarea rows="2" cols="25" class="form-control sub_task_descri" ></textarea>

						</div>
					</div>
					<div class="form-group " style="text-align: center;margin-right: 65px">

						<button type="submit" class="layui-btn choose" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fcfcfc" >
							{{trans('Task/displayAllocateSubTask.8')}}
						</button>
						<div style="width:600px;height:60px;margin-left:308px;margin-top: 20px;color: black;" class="user_box scroll"  >
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<input type='hidden' name='' id='task_id' value='{{$return_data['task_id']}}'>
</form>
<div style="height:200px;width: 1030px;margin-top: 40px">
	<div style="float: right;margin-right: 30px;">
		<button id="submit" type="submit" class="layui-btn shadow commit" style="border: 2px solid #0C0C0C;color: #0C0C0C" >
			{{trans('Task/displayAllocateSubTask.9')}}
		</button>
	</div>
</div>
<span style="opacity: 0">{{$i=0}}</span>
<div style="display: none">
	<div style="width: 1030px;height:300px;float: left;margin-top: 20px;" class="stask_form" id="{{$i}}" >
		<span style="opacity: 0"> {{$i=$i+1}}</span>
		<div style="width:900px;height: 300px; margin-left: auto;margin-right: auto;border: 3px solid black;" >
			<div style="width: 850px;height:180px;float: left;margin-top: 20px">
				<div class="form-group" style="margin-bottom: 30px;">
					<label for="stask_name" class="col-md-4 control-label" >{{trans('Task/displayAllocateSubTask.6')}}</label>
					<div class="col-md-6"  >
						<input type="text" required="" class="form-control sub_task_name" >
					</div>
				</div>
				<div class="form-group" style="margin-bottom: 20px;" >
					<label for="stask_descri" class="col-md-4 control-label">{{trans('Task/displayAllocateSubTask.7')}}</label>
					<div class="col-md-6"  >
						<textarea rows="2" cols="25" class="form-control sub_task_descri"></textarea>
					</div>
				</div>
				<div class="form-group " style="text-align: center;margin-right: 65px">
					<button type="submit" class="layui-btn choose" style="border: 2px solid #0C0C0C;color: #0C0C0C;background-color: #fcfcfc" >
						{{trans('Task/displayAllocateSubTask.8')}}
					</button>
					<div style="width:600px;height:60px;margin-left:308px;margin-top: 20px;color: black;" class="user_box scroll"  >

					</div>
				</div>
			</div>
			<div style="float: right;" class="del" >
				<img src="{{URL::asset('/images/delete2.png')}}" >
			</div>
		</div>
	</div>
</div>
@endsection
