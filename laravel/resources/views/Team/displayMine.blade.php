{{--用户已加入的所有团队的展示页面--}}
@extends('layouts.home')
@section('content')
		{{--展示Team--}}
		<div class="portfolio-grid portfolioContainer "  STYLE="width: 100%;height: 100%;">
			<ul id="thumbs" class="col3"  style="width: 100%;height: 100%; ">

				@foreach($pageout as $team)
				{{--每一个Team都放在一个li中，包括信息与图片--}}
				<li style="width: 350px;height: 265px;margin-left: 120px;margin-bottom: 80px;margin-top: 20px;">
					<div class="portfolio-image-wrapper">
						<img src="{{URL::asset('/images/team.png')}}" alt=""  style="border:3px dashed #000"/>
						<div class="item-info-overlay">

							<a href="#" class="overlay-link"></a>
							<h3 class="text-white semi-bold p-t-60 project-title " style="color: #fff200;font-weight: 900;">
								Team Name:{{ $team['team_name'] }}</h3>
							<P> </P>
							<p class="project-description">
								Team Founder:{{ $team['team_funder_id'] }}</p>
							<p class="project-description">
								Team Intro:{{ $team['team_info'] }}</p>

						</div>

					</div>
					<div class="item-info">
						<h4 class="text-dark no-margin p-t-10">
							Team Name:{{ $team['team_name'] }}</h4>
						<p>
							Established At :{{ date('Y-m-d H:i:s',$team['created_at']) }}</p>

					</div>
					{{--对Team可进行的操作--}}
					<div style="float: right;position: relative;z-index: 1;top:300px;">
						<div class="btn-group">
							<button type="button" class="dropdown-toggle team_func" data-toggle="dropdown">
							</button>
							<input type="hidden" name="team_id" value="{{ $team['team_id'] }}">
							<ul class="dropdown-menu" role="menu">
								<li><a href="#">Change Team Name</a></li>
								<li id="quit-team"><a href="#">Quit/Dismiss</a></li>
								<li><a href="#">others</a></li>

							</ul>
						</div>
					</div>
				</li>
				@endforeach
			{{--	<li style="position: relative;width: 350px;height: 265px;margin-left: 120px;margin-top: 20px;">
					<div class="portfolio-image-wrapper" style="border:3px dashed #000">



					</div>
				</li>--}}

			</ul>

		</div>

<script type="text/javascript">
	$(function(){
		layui.use('layer',function(){
			$('ul.dropdown-menu').on('click','#quit-team',function(){
				var team_id = $(this).parent().siblings("[name='team_id']").val();
				$.ajax({
					url: "{{ url('team/quit') }}",
					type: 'get',
					dataType: 'json',
					data: {'team_id': team_id,"_token":"{{csrf_token()}}"}
				})
				.done(function(data) {
					layer.msg(data.msg,{
							icon:data.icon
					});
					if(data.icon==='1'){
						setTimeout("displayMine()",1500);
						
					}
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
	function displayMine(){
		location.href="{{ url('team/displayMine') }}";
	}
</script>
		<div >
			{{ $paged->links() }}
		</div>
@endsection