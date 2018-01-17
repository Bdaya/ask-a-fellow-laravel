<?php
$page = 0;
if(isset($_GET['page']) && $_GET['page'] > 0)
    $page = $_GET['page'];
$take = 10;
if(isset($_GET['take']) && $_GET['take'] > 0)
    $take = $_GET['take'];

$pages = ceil($count_questions/$take);


?>

@extends('layouts.app')

@section('content')

    <div class="container" style="margin-top:-80px;padding-left: 50px; width: 100%;">

        <h2>Hello {{Auth::user()->first_name}}!</h2>
        <p>Showing questions from your <a href="{{url('/subscriptions')}}">subscribed courses</a>.</p>
        <h4 class="pull-right"><a href="{{url('/add_component')}}">Add a new component</a></h4>
        <h3><a href="{{url('/user/components')}}">View all the available components</a></h3>
        <h3><a href="{{url('/user/stores')}}">View all the available stores</a></h3>
        <h4 class="pull-right"><a href="{{url('/browse')}}">Ask a question</a></h4>
        <h3><a href="{{url('/browse')}}">Browse all courses</a></h3>
        <hr>

        @if(count($questions) == 0)
            <h3>There are no questions to show. You can manage your subscriptions <a href="{{url('/subscriptions')}}">here</a>.</h3>

        @endif

        <nav class="center-block" style="text-align: center">
            <ul class="pagination">
                @if($page > 0)
                    <li><a href="?page={{$page - 1}}&take={{$take}}" aria-label="Previous"><span aria-hidden="true">«</span></a> </li>
                @endif
                @for($i = 0; $i < $pages; $i++)
                    @if($page == $i)
                        <li class="active"><a href="?page={{$i}}&take={{$take}}">{{$i + 1}} <span class="sr-only">(current)</span></a></li>
                    @else
                         <li><a href="?page={{$i}}&take={{$take}}">{{$i + 1}}</a></li>
                    @endif

                @endfor
                @if($page < $pages - 1)
                    <li class="{{$page >= $pages-1? 'disabled':''}}"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                @endif
            </ul>
            <form action="" method="GET" class="center-block" >
                <label for="take">Questions/Page</label>
                <input id="take" class="form-control center-block" min="1" max="20" style="width: 70px;display: inline-block" type="number" name="take"  value="{{$take}}">
                <input style="" class="btn btn-sm btn-default" type="submit" value="Update">
            </form>
        </nav>
        @foreach($questions as $question)
            <div href="{{url('answers/'.$question->id)}}" class="media question">
                <div style="text-align: center" class="media-left">

                    <a href="{{url('user/'.$question->asker_id)}}">
                        @if($question->asker->profile_picture)
                            <img class="media-object" src="{{asset($question->asker->profile_picture)}}" alt="...">

                        @else
                            <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="...">

                        @endif
                    </a>
                    @if(Auth::user())
                        <a class="upvote_question vote" value="{{$question->id}}" title="upvote" style="color:green;"><span class="glyphicon glyphicon-thumbs-up"></span></a>
                    @endif
                    @if($question->votes > 0)
                        <span class="question_votes" style="color:green;">{{$question->votes}} </span>
                    @elseif($question->votes == 0)
                        <span class="question_votes" style="">{{$question->votes}} </span>
                    @else
                        <span class="question_votes" style="color:red;">{{$question->votes}} </span>
                    @endif
                    @if(Auth::user())
                        <a class="downvote_question vote" value="{{$question->id}}" title="downvote" style="color:red"><span class="glyphicon glyphicon-thumbs-down"></span></a>
                    @endif
                </div>
                <div class="media-body" style="">
                    @if($question->asker->verified_badge >=1)
                        <h3>{{$question->asker->first_name.' '.$question->asker->last_name}}<span class="verified"></span></h3>
                    @else
                        <h3>{{$question->asker->first_name.' '.$question->asker->last_name}}</h3>
                    @endif
                    <h5 style="color:green">{{$question->course->course_name}}</h5>
                    <div class="question_text">
                        {{$question->question}}
                    </div>
                    <p style=" font-style: italic; ">{{ date("F j, Y, g:i a",strtotime($question->created_at)) }} </p>
                    @if(count($question->answers) > 0)
                        <div class="media answer">
                            <div style="text-align: center" class="media-left">

                                <a href="{{url('user/'.$question->answers()->orderBy('answers.votes','desc')->first()->responder_id)}}">

                                    @if($question->answers()->orderBy('answers.votes','desc')->first()->responder->profile_picture)
                                        <img class="media-object" src="{{asset($question->answers()->orderBy('answers.votes','desc')->first()->responder->profile_picture)}}" alt="...">

                                    @else
                                        <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="...">
                                        @if($question->answers()->orderBy('answers.votes','desc')->first()->responder->verified_badge >= 1)


                                        @endif
                                    @endif
                                </a>
                            </div>
                            <div class="media-body">
                                @if($question->answers()->orderBy('answers.votes','desc')->first()->responder->verified_badge >= 1)
                                    <h3>{{$question->answers()->orderBy('answers.votes','desc')->first()->responder->first_name.' '.$question->answers()->orderBy('answers.votes','desc')->first()->responder->last_name}}<span class="verified"></span> <span class="pull-right label label-success">Top Answer</span></h3>
                                @else
                                    <h3>{{$question->answers()->orderBy('answers.votes','desc')->first()->responder->first_name.' '.$question->answers()->orderBy('answers.votes','desc')->first()->responder->last_name}} <span class="pull-right label label-success ">Top Answer</span></h3>
                                @endif

                                <div class="answer_text">
                                    {{$question->answers()->orderBy('answers.votes','desc')->first()->answer}}
                                </div>
                                <p style=" font-style: italic; ">{{ date("F j, Y, g:i a",strtotime($question->answers()->orderBy('answers.votes','desc')->first()->created_at)) }} </p>
                            </div>

                        </div>
                        <span class="pull-right" style="font-weight: bold"><a href="{{url('/answers/'.$question->id)}}">Show all answers ({{count($question->answers)}})</a></span>
                    @else
                        <span class="pull-right" style="color:red">This question has no answers yet. <a href="{{url('/answers/'.$question->id)}}">Be the first to answer!</a></span>
                    @endif
                </div>


            </div>

        @endforeach


        <nav class="center-block" style="text-align: center">
            <ul class="pagination">
                @if($page > 0)
                    <li><a href="?page={{$page - 1}}&take={{$take}}" aria-label="Previous"><span aria-hidden="true">«</span></a> </li>
                @endif
                @for($i = 0; $i < $pages; $i++)
                    @if($page == $i)
                        <li class="active"><a href="?page={{$i}}&take={{$take}}">{{$i + 1}} <span class="sr-only">(current)</span></a></li>
                    @else
                        <li><a href="?page={{$i}}&take={{$take}}">{{$i + 1}}</a></li>
                    @endif

                @endfor
                @if($page < $pages - 1)
                    <li class="{{$page >= $pages-1? 'disabled':''}}"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                @endif
            </ul>
        </nav>

</div>


    <style>
        .question
        {
            background-color:  #FFF5E9;
            padding: 15px;
            margin-left: 80px;
            width: 80%;
            /*min-width: 200px;*/
            margin-bottom: 10px;
            border: 1px solid #E8DAC9;
            border-radius: 10px;
        }
        .question img
        {
            width: 50px;
            height: 50px;
            border-radius: 100px;
            margin-bottom: 10px;
        }
        .question h3
        {
            /*width: 100%;*/
            font-size: 18px;
            margin-top: 2px;
            color: #621708;
            /*font-weight: bold;*/
        }

        .question .question_text
        {
            font-size: 18px;
            background-color: #F9EBDA;
            padding: 15px;
            
            /*display: inline-block;*/
        }





        .answer
        {
            background-color:  #F5E0C2;
            padding: 15px;
            /*margin-left: 80px;*/
            /*width: 60%;*/
            /*min-width: 200px;*/
            margin-bottom: 10px;
        }
        .answer img
        {
            width: 50px;
            height: 50px;
            border-radius: 100px;
            margin-bottom: 10px;
        }
        .answer h3
        {
            /*width: 100%;*/
            font-size: 18px;
            margin-top: 2px;
            color: #621708;
            /*font-weight: bold;*/
        }

        .answer .answer_text
        {
            font-size: 15px;
        }

        .pagination a
        {
            color: #E66900 !important;
            background-color: #FDF9F3 !important;
        }
        .pagination .active a
        {
            background-color: #FFAF6C !important;
            border-color: #CC8C39;
            color: #BD5D0D !important;
        }

        @media (max-width:800px)
        {
           .question
           {
               margin-left:-30px;
               min-width: 300px;
               width: 90%;
           }

        }
        span.verified{
            display: inline-block;
            vertical-align: middle;
            height: 40px;
            width: 40px;
            background-image: url("{{asset('art/ver.png')}}");
            background-repeat: no-repeat;
            z-index: 200000;

        }
    </style>

    <script>
        $('.upvote_question').click(function(){
            var question_id = $(this).attr('value');
            var type = 0;
            var question = $(this);
            $.ajax({
                'url' : "{{url('')}}/vote/question/"+question_id+"/"+type,
                success: function(data){
                    question.parent().find('.question_votes').html(data);
                }
            });
        });

        $('.downvote_question').click(function(){
            var question_id = $(this).attr('value');
            var type = 1;
            var question = $(this);
            $.ajax({
                'url' : "{{url('')}}/vote/question/"+question_id+"/"+type,
                success: function(data){
                    question.parent().find('.question_votes').html(data);
                }
            });
        });
    </script>
@endsection
