{{--当前用户所创建团队的展示页面--}}
@extends('layouts.home')
@section('content')
		{{--展示Team--}}
		<div class="portfolio-grid portfolioContainer "  STYLE="width: 100%;height: 100%;">
			<ul id="thumbs" class="col3"  style="width: 100%;height: 100%; ">
				{{--搜索框--}}

				<div class="search_input">

					<form method="post" style="background-color: #34bf49;border-radius: 42px;padding: 4px" action="{{ url('team/displaySearchMineCre') }}">
					 <input type="hidden" name="_token" value="{{csrf_token()}}">
						<div class="btn-group" >
							<button type="button" class="search">
							</button>
						</div>
						<input name='search-key' type="text" placeholder="{{trans('Team/displayMine.1')}}" >

						<div class="btn-group" style="margin-left: 630px;">
							<button type="button" class="dropdown-toggle sort" data-toggle="dropdown">
							</button>
							<ul class="dropdown-menu" role="menu" style="z-index: 9999;margin-top: 5px;">
								<!-- <li><a href="#">All</a></li> -->
								<li><a href="{{ url('team/displayMineCre/team.created_at') }}">{{trans('Team/displayMine.2')}}</a></li>
								<li><a href="{{ url('team/displayMineCre/team.team_name') }}">{{trans('Team/displayMine.3')}}</a></li>
								<li><a href="{{ url('team/displayMineCre/team.team_funder_id') }}">{{trans('Team/displayMine.4')}}</a></li>

							</ul>
						</div>

					</form>
				</div>
				@if($pageout==null)
					<div style="margin-top: 20%;">
						<p class="Big_Font">{{trans('Team/displayMine.13')}}<br>{{trans('Team/displayMine.6')}} <a href="{{url('team/displayAdd')}}" class="Big_Font">{{trans('Team/displayMine.7')}}</a> {{trans('Team/displayMine.8')}}</p>
					</div>



				@else

				@foreach($pageout as $team)
				{{--每一个Team都放在一个li中，包括信息与图片--}}
				<li style="width: 350px;height: 265px;margin-left: 120px;margin-bottom: 60px;margin-top: 50px;">
					<div class="portfolio-image-wrapper">
						<img src="{{URL::asset('/images/team.png')}}" alt=""  style="border:3px dashed #000"/>
						<div class="item-info-overlay">

							<div style="width: 280px;height: 40px;white-space:nowrap;overflow: hidden;text-overflow: ellipsis;">
								<h3 class="text-white semi-bold p-t-60 project-title " style="color: #fff200;font-weight: 900;">
									{{trans('Team/displayMine.9')}}{{ $team['team_name'] }}</h3>
							</div>
							<P> </P>
							<div style="width: 280px;height: 30px;white-space:nowrap;overflow: hidden;text-overflow: ellipsis;">
								<p class="project-description">
									{{trans('Team/displayMine.10')}}{{ $team['user_name'] }}</p>
							</div>
							<div style="width: 280px;height: 20px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap">
								<p class="project-description">
									{{trans('Team/displayMine.11')}}{{ $team['team_info'] }}</p>
							</div>
							<div style="width: 300px;	" class="project-description">
								<button class="view_icon" onclick='javascrtpt:window.location.href="{{url('team/displayOne',['team_name'=> $team['team_name']])}}"'> </button>
							</div>

						</div>

					</div>
					<div class="item-info">
						<h4 class="text-dark no-margin p-t-10">
							{{trans('Team/displayMine.9')}}{{ $team['team_name'] }}</h4>
						<p>
							{{trans('Team/displayMine.12')}}{{ date('Y-m-d H:i:s',$team['created_at']) }}</p>

					</div>
					{{--对Team可进行的操作--}}
					<div style="float: right;position: relative;z-index: 1;top:300px;">
						<div class="btn-group">
							<button type="button" class="dropdown-toggle team_func" data-toggle="dropdown">
							</button>
							<input type="hidden" name="team_id" value="{{ $team['team_id'] }}">
							<ul class="dropdown-menu" role="menu" style="z-index: 9999">
								<!-- <li><a href="#">Change Team Name</a></li> -->
								<li><a href="{{url('team/displayOne',$team['team_name'])}}">{{trans('Team/displayMine.14')}}</a></li>
								<li><a href="{{url('user/displayAllForAdd',$team['team_name'])}}">{{trans('Team/displayMine.15')}}</a></li>
								<li><a href="{{url('task/displayAdd',$team['team_name'])}}">{{trans('Team/displayMine.16')}}</a></li>
								<!-- <li id="quit-team"><a href="#">Quit/Dismiss</a></li> -->
								<li><a href="#">others</a></li>

							</ul>
						</div>
					</div>
				</li>
				@endforeach
				@endif
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