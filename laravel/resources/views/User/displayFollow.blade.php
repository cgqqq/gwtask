@extends('layouts.home')

@section('content')

<script type="text/javascript">
	$(function(){
		layui.use('layer',function(){
			$('.layui-table').on('click','.follow-btn',function(){
				var followed_id = $(this).parent().siblings('.td-id').text();
				$.ajax({
					url: '{{ url('user/follow') }}',
					type:'get',
					dataType: 'json',
					data: {'type': 'unfollow','followed_id':followed_id,"_token":"{{csrf_token()}}"},
				})
				.done(function(data) {
					layer.msg(data.msg,{
						icon:data.icon,
					});
					if(data.icon==='1'){
						setTimeout("displayFollow()",750);
					}
				})
				.fail(function() {
					layer.msg('服务器为响应!',{
						icon:5
					});
				})
				.always(function() {
					console.log("complete");
				});
			});
		});		
	});

	function displayFollow(){
		location.href="{{ url('user/displayFollow') }}";
	}
</script>

<table class="layui-table" style="width: 900px;">
	<thead>
		<tr>
			<td>
				用户ID
			</td>
			<td>
				姓名
			</td>
			<td>
				邮箱
			</td>
			<td>
				操作
			</td>
		</tr>
	</thead>
	@foreach($pageOut as $user)
		<tr>
			<td class="td-id">
				{{ $user['user_id']}}
			</td>
			<td>
				{{ $user['user_name'] }}
			</td>
			<td>
				{{ $user['user_email'] }}
			</td>			
			<td>
				<button class="follow-btn">取关</button>
			</td>
		</tr>
	@endforeach
</table>
{{ $paged->links() }}
@endsection