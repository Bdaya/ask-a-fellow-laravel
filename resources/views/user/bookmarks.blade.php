@extends('user.profile')
@section('question_answer_section')
    <nav id="switch" class="center-block cl-effect-21" style="text-align:center;width: 100%; height: 70px;">
        <a id="loginSwitch" style="opacity:0.5;margin-right:3%; color: #CA6A1B;  margin-left:5%; margin-bottom:15px; border-bottom:1px solid #CA6A1B;" href="#">Questions</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="{{url('user/'.$user->id)}}/answers">Answers</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="{{url('user/'.$user->id)}}/calender">Calender</a>
        <a id="registerSwitch" style="margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="{{url('user/'.$user->id)}}/bookmarks">Bookmarks</a>
    </nav>

    <h3>{{$user->first_name}} bookmarked {{count($user->bookmarked_questions()->get())}} question(s).</h3>
    <br>
    @foreach($user->bookmarked_questions()->get() as $bookmark)

        <div class="media">
		    <div style="text-align: center" class="media-left">
		        <a href="#">
		            @if($bookmark->question->asker->profile_picture)
		                <img class="media-object" src="{{asset($user->profile_picture)}}" alt="...">
		            @else
		                <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="...">
		            @endif

		        </a>
		        @if($bookmark->question->votes > 0)
		            <span style="color:green;">{{$bookmark->question->votes}} <span class="glyphicon glyphicon-thumbs-up"></span></span>
		        @elseif($bookmark->question->votes == 0)
		            <span style="">{{$bookmark->question->votes}} <span class="glyphicon glyphicon-thumbs-up"></span></span>
		        @else
		            <span style="color:red;">{{$bookmark->question->votes}} <span class="glyphicon glyphicon-thumbs-down"></span></span>
		        @endif

		    </div>
		    <div class="media-body">
		        <h4 class="media-heading">{{substr($bookmark->question->question,0,50).'...'}}</h4>
		        {{substr($bookmark->question->question,0,300).'....'}}<a href="{{url('/answers/'.$bookmark->question->id)}}">See full question.</a>
		        <p style="font-weight: bold; font-style: italic; font-size: 13px;">{{ date("F j, Y, g:i a",strtotime($bookmark->question->created_at)) }} </p>
		    </div>

		</div>

    @endforeach

     <h3>{{$user->first_name}} bookmarked {{count($user->bookmarked_components_questions()->get())}} components question(s).</h3>
    <br>
    @foreach($user->bookmarked_components_questions()->get() as $bookmark)

    	 <div class="media">
		    <div style="text-align: center" class="media-left">
		        <a href="#">
		            @if($bookmark->question->asker->profile_picture)
		                <img class="media-object" src="{{asset($user->profile_picture)}}" alt="...">
		            @else
		                <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="...">
		            @endif

		        </a>
		    </div>

		    <div class="media-body">
		        <h4 class="media-heading">{{substr($bookmark->question->question,0,50).'...'}}</h4>
		        {{substr($bookmark->question->question,0,300).'....'}}<a href="{{url('/user/view_component_answers/'.$bookmark->question->id)}}">See full question.</a>
		        <p style="font-weight: bold; font-style: italic; font-size: 13px;">{{ date("F j, Y, g:i a",strtotime($bookmark->question->created_at)) }} </p>
		    </div>

		</div>

       
    @endforeach

@endsection