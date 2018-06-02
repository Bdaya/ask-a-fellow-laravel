@extends('user.profile')
@section('question_answer_section')
    <nav id="switch" class="center-block cl-effect-21" style="text-align:center;width: 100%; height: 70px;">
        <a id="loginSwitch" style="margin-right:3%; color: #CA6A1B;  margin-left:5%; margin-bottom:15px; border-bottom:1px solid #CA6A1B;" href="#">Questions</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="{{url('user/'.$user->id)}}/answers">Answers</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="{{url('user/'.$user->id)}}/calender">Calender</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="{{url('user/'.$user->id)}}/bookmarks">Bookmarks</a>
    </nav>

    <h3>{{$user->first_name}} asked {{count($user->questions()->get())}} question(s).</h3>
    <br>
    @foreach($user->questions()->get() as $question)
        <div class="media">
            <div style="text-align: center" class="media-left">
                <a href="#">
                    @if($user->profile_picture)
                        <img class="media-object" src="{{asset($user->profile_picture)}}" alt="...">
                    @else
                        <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="...">
                    @endif

                </a>
                @if($question->votes > 0)
                    <span style="color:green;">{{$question->votes}} <span class="glyphicon glyphicon-thumbs-up"></span></span>
                @elseif($question->votes == 0)
                    <span style="">{{$question->votes}} <span class="glyphicon glyphicon-thumbs-up"></span></span>
                @else
                    <span style="color:red;">{{$question->votes}} <span class="glyphicon glyphicon-thumbs-down"></span></span>
                @endif

            </div>
            <div class="media-body">
                <h4 class="media-heading">{{substr($question->question,0,50).'...'}}</h4>
                {{substr($question->question,0,300).'....'}}<a href="{{url('/answers/'.$question->id)}}">See full question.</a>
                <p style="font-weight: bold; font-style: italic; font-size: 13px;">{{ date("F j, Y, g:i a",strtotime($question->created_at)) }} </p>
            </div>

        </div>
    @endforeach

@endsection