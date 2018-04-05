@extends('layouts.home')

@section('content')

<script type="text/javascript">
	$(function(){
		layui.use('layer',function(){
			$('.layui-table').on('click','.follow-btn',function(){
				var followed_id = $(this).parent().siblings('.td-id').val();
				var type = null;
				var msg = null;
				if($(this).text()==='关注'){
					type = 'follow';
					msg = '关注';
				}else{
					type = 'unfollow';
					msg = '取关';
				}
				if(followed_id =={{ session('user_id') }}){
					layer.tips('不可以'+msg+'自己!',$(this),{
						tips:1
					});
				}else{
					$.ajax({
						url: '{{ url('user/follow') }}',
						type: 'get',
						dataType: 'json',
						data: {'type':type,'followed_id':followed_id,"_token":"{{csrf_token()}}"},
					})
					.done(function(data) {
						layer.msg(data.msg,{
							icon:data.icon
						});
					})
					.fail(function() {
						layer.msg('服务器未响应!',{
							icon:5
						});
					})
					.always(function() {
						console.log("complete");
					});
					
				}
			});

			$('.layui-table').on('click','.btn',function(){
				var url = null,data=null,user_id = $(this).parent().siblings('td-id').val();
				if($(this).text()==='修改'){
					url = "{{ url('user/edit') }}";
					data = "{'user_id':"+user_id+",}";
				}else{
					url = "{{ url('user/del') }}";
					data = "{'user_id':"+user_id+"}";
				}
				$.ajax({
					url: url,
					type: 'get',
					dataType: 'json',
					data: data,
				})
				.done(function(data) {
					location.reload();
					layer.msg(data.msg,{
						icon:data.icon
					});				
				})
				.fail(function() {
					layer.msg('服务器未响应!',{
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

<table class="layui-table" style="width: 900px;">
	<thead>
		<tr>
			<td>
				Name
			</td>
			<td>
				E-mail
			</td>
			<td colspan="2">
				Operation
			</td>
		</tr>
	</thead>
	@foreach($users as $user)
		<tr>
		<input type="hidden" class="td-id" value="{{ $user->user_id }}" name="">
			<td>
				{{ $user->user_name }}
			</td>
			<td>
				{{ $user->user_email }}
			</td>
			<!-- <td>
				<button class="btn" >修改</button>
			</td
			><td>
				<button class="btn">删除</button>
			</td> -->
			<td>
				<button class="layui-btn layui-btn-sm follow-btn" >关注</button>
			</td>
			<td>
				<button class="layui-btn layui-btn-sm follow-btn">取关</button>
			</td>
		</tr>
	@endforeach
</table>
{{ $users->links() }}
@endsection