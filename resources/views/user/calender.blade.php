@extends('user.profile')
@section('question_answer_section')
    <nav id="switch" class="center-block cl-effect-21" style="text-align:center;width: 100%; height: 70px;">
        <a id="loginSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="#">Questions</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="{{url('user/'.$user->id)}}/answers">Answers</a>
        <a id="registerSwitch" style="    margin-right:3%; color: #CA6A1B;  margin-left:5%; margin-bottom:15px; border-bottom:1px solid #CA6A1B;" href="{{url('user/'.$user->id)}}/calender">Calender</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="{{url('user/'.$user->id)}}/bookmarks">Bookmarks</a>
    </nav>

    @if($calender == null)
        <div style="text-align: center;">
            <h4>You don't have a calender yet, click the button to create one</h4>
            <form action="{{url('calender/create')}}" method="post">
                <input class="btn btn-default submit"
                       style="background-color:#FF953D; border: 1pxx solid #CC953D;" href="#"
                       type="submit" value="create" placeholder="create">
            </form>
        </div>

    @else

        @foreach($events as $event)

            <div href="{{url('events/'.$event->id)}}" class="media question">
                <div style="text-align: center" class="media-left">

                    <a href="{{url('user/'.$event->creator_id)}}">
                        @if($event->creator->profile_picture)
                            <img class="media-object" src="{{asset($event->creator->profile_picture)}}" alt="...">

                        @else
                            <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="...">
                        @endif
                    </a>


                </div>
                <div class="media-body" style="cursor: pointer;">
                    @if(Auth::user())
                        <div class="delete_question pull-right">
                            @if(Auth::user()->id == $event->creator_id || Auth::user()->role >= 1)

                                <a onclick="return confirm('Are you sure?');" title="Delete event"
                                   href="{{url('delete_event/'.$event->id)}}"><span style="color:#FFAF6C"
                                                                                    class="glyphicon glyphicon-remove"></span></a>

                            @endif

                        </div>
                    @endif
                    @if($event->creator->verified_badge >=1)
                        <h3>{{$event->creator->first_name.' '.$event->creator->last_name}} <span class="verified"></span></h3>
                    @else
                        <h3>{{$event->creator->first_name.' '.$event->creator->last_name}} </h3>
                    @endif
                    <div class="question_text">
                        <h3>{{$event->title}}</h3>
                        <p class="pull-right">{{$event->place}}</p>
                        <h5>{{$event->description}}</h5>
                    </div>
                    <p style="font-weight: bold; font-style: italic; ">{{ date("F j, Y, g:i a",strtotime($event->date)) }} </p>
                </div>


            </div>

        @endforeach
    @endif


@endsection