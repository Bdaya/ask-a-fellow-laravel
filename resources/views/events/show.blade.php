@extends('layouts.app')
@section('content')

    <div class="container">
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
                @if($event->creator->verified_badge >=1)
                    <h3>{{$event->creator->first_name.' '.$event->creator->last_name}} <span class="verified"></span>
                    </h3>
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

        <div>
            <h3>Announcement(s)</h3>
        @if($announcements->count() == 0)
            <p>There are no announcements yet for this event</p>
        @else
            @foreach($announcements as $announcement)
                <div  class="media question">
                    <div style="text-align: center" class="media-left">

                        <a href="{{url('user/'.$announcement->user_id)}}">
                            @if($announcement->user->profile_picture)
                                <img class="media-object" src="{{asset($announcement->user->profile_picture)}}" alt="...">

                            @else
                                <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="...">
                            @endif
                        </a>


                    </div>
                    <div class="media-body" style="cursor: pointer;">

                        @if($announcement->user->verified_badge >=1)
                            <h3>{{$announcement->user->first_name.' '.$announcement->user->last_name}} <span
                                        class="verified"></span>
                            </h3>
                        @else
                            <h3>{{$announcement->user->first_name.' '.$announcement->user->last_name}} </h3>
                        @endif
                        <div class="question_text">
                            <h3>{{$announcement->title}}</h3>
                            <h5>{{$announcement->description}}</h5>
                        </div>
                        <p style="font-weight: bold; font-style: italic; ">{{ date("F j, Y, g:i a",strtotime($announcement->created_at)) }} </p>
                    </div>


                </div>
            @endforeach
        @endif

        @if(Auth::user() && (Auth::user()->role >= 1 || Auth::user()->id == $event->creator->id))

            <h4>Add Announcement</h4>
            <form id="add_announcement_form" action="{{ url('event/add_announcement/'.$event->id) }}" method="POST">
                <div class="form-group row">
                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" id="title" name="title" placeholder="title">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-5">
                        <textarea class="form-control" id="description" rows="3" name="description" placeholder="description"></textarea>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-offset-3 col-sm-10">
                        <button type="submit" class="btn btn-primary">Add announcement</button>
                    </div>
                </div>
            </form>
        @endif
      </div>        

    </div>

    @if (Session::has('error'))
        <div class="alert alert-danger">{{ Session::get('error') }}</div>
    @endif

    <style>
        #filtration_form {
            width: 20%;
            float: right;
            /*display: inline-block;*/

        }

        .question {
            background-color: #FFF5E9;
            padding: 15px;
            /*margin-left: 80px;*/
            width: 70%;
            /*min-width: 200px;*/
            margin-bottom: 10px;
        }

        .question img {
            width: 50px;
            height: 50px;
            border-radius: 100px;
            margin-bottom: 10px;
        }

        .question h3 {
            /*width: 100%;*/
            font-size: 18px;
            margin-top: 2px;
            color: #621708;
            /*font-weight: bold;*/
        }

        .question .question_text {
            font-size: 15px;
            max-width: 600px;
        }

        .vote {
            cursor: pointer;
        }

        .question .media-body {
            cursor: pointer;
        }

        .question:hover {
            background-color: #F5E0C2;
        }

        #post_question_form {
            width: 60%;
            margin-left: 90px;
            margin-top: 50px;
        }

        #post_question_form textarea {
            resize: none;
            height: 150px;
            font-size: 18px;
        }

        #post_question_form #post_question_submit {
            background-color: #FFE9CF;
            border: 1px solid #CCB69C;
            margin-top: 10px;
        }

        #post_question_form #post_question_submit:focus {
            background-color: #CCB69C;
            /*border: 1px solid #CCB69C !important;*/

        }

        .pagination a {
            color: #E66900 !important;
            background-color: #FDF9F3 !important;
        }

        .pagination .active a {
            background-color: #FFAF6C !important;
            border-color: #CC8C39;
            color: #BD5D0D !important;
        }

        .media img + span.badge {
            position: relative;
            top: -8px;
            left: 8px;
        }

        #filtration_form {
            min-width: 150px;
        }

        @media (max-width: 800px) {
            .question, #post_question_form {
                margin-left: -30px;
                min-width: 300px;
                width: 90%;
            }

        }

        span.verified {
            display: inline-block;
            vertical-align: middle;
            height: 40px;
            width: 40px;
            background-image: url("{{asset('art/ver.png')}}");
            background-repeat: no-repeat;
            z-index: 200000;

        }

    </style>
@endsection