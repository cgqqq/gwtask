@extends('layouts.home')

@section('content')


		<div class="following" style="margin-bottom: 25px;">
			<div style="float: left;margin-bottom: 40px">
				<p style="font-size: 50px;font-family: 'Source Sans Pro', sans-serif;font-weight: 800;color: #333333;width: 500px;">MY FOLLOWINGS</p>
			</div>

			{{--搜索框--}}
			<div class="search_input" style="width: 400px;float: right;margin-top: 15px;">
				<form style="background-color: #34bf49;border-radius:40px;padding-right: 5px; float: right;">
					<div class="btn-group" >
						<button type="button" class="search" data-toggle="dropdown">
						</button>
					</div>
					<input type="text" placeholder="Search With User ID……" style="border: 1px #000;background-color: #fcfcfc;color: #0C0C0C" >

				</form>

			</div>



			{{--关注用户列表--}}
			@foreach($pageOut as $user)
			<div class="chip">
					<img src="{{URL::asset('/uploads/user_profile/'.$user['user_profile'])}}" alt="IMG" style="margin-right: 20px"/>
					{{ $user['user_name'] }}
					{{--关注与取关操作--}}

					<div class="switch" style="float: right;margin-right: 10px;">
						<input type="hidden" value="{{ $user['user_id'] }}" name="td-id">
						<label>
							Unfollow
							<input class="isFollow" type="hidden" value="1">
							<input type="checkbox" checked="checked" class="fo_checkbox">
							<span class="lever"></span>
							Follow
						</label>
					</div>
			</div>
			@endforeach
		</div>

<script type="text/javascript">


	$(function(){
		layui.use('layer',function(){
			$('.switch').on('click','.fo_checkbox',function(){
				var followed_id = $(this).parent().siblings([name='td-id']).val();
				var action = null;
				var isFollow = $(this).prev().val();
				if(isFollow=='1'){
					$(this).prev().val('0');
					action = 'unfollow';
				}else{
					$(this).prev().val('1');
					action = 'follow';
				}
                $.ajax({
                    url: '{{ url('user/follow') }}',
                    type:'get',
                    dataType: 'json',
                    data: {'type': action,'followed_id':followed_id,"_token":"{{csrf_token()}}"},
                })
                    .done(function(data) {
                        layer.msg(data.msg,{
                            icon:data.icon,
                        });
                        // if(data.icon==='1'){
                        //     setTimeout("displayFollow()",750);
                        // }
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

	function displayFollow(){
		location.href="{{ url('user/displayFollow') }}";
	}
</script>
{{ $paged->links() }}
@endsection