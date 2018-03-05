@extends('layouts.home')

@section('content')

    @if($pageout)
        <span style="color: #000;font-size: 20px">
            Result(s)
        <div class="collection" style="margin-top: 50px">

            @foreach($pageout as $team)

            <a href="#!" class="collection-item"><span class="new badge" style="margin-top: 25px">未计算</span>
                {{ $team['team_name'] }}
                <span style="color: #8D8D8D">
                    Team Founder:{{ $team['team_funder_id'] }}
                </span>
                <p>
                    BIO:{{$team['team_info']}}
                </p>
            </a>

            @endforeach
        </div>

        </span>
        <div style="margin-top: 10px">
            {{ $paged->links() }}
        </div>

    @else
        <div style="margin-top: 150px;margin-left: 450px">
            <span style="color: #8D8D8D;font-size: 20px;">NO RESULT</span>


        </div>

    @endif


<div>


</div>
@endsection
