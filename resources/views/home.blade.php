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

<link rel="stylesheet" type="text/css" href="{{ url('/css/main.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="home-page">
  <div class="container">

    <div class="home-page__content">

      <h2>Hello {{Auth::user()->first_name}}!</h2>
      <p>Showing questions from your <a class="text" href="{{url('/subscriptions')}}">subscribed courses</a>.</p>

      <div class="row">
        <div class="col-md-8">

          <nav class="center-block" style="text-align: center">
            <form action="" method="GET" class="center-block" >
              <label for="take">Questions/Page</label>
              <input id="take" class="form-control center-block" min="1" max="20" style="width: 70px; height: 30px; display: inline-block" type="number" name="take"  value="{{$take}}">
              <input class="btn btn-sm btn-default" type="submit" value="Update">
            </form>
          </nav>

          @if(count($questions) == 0)
            <div class="card__body">
              <h4>There are no questions to show. You can manage your subscriptions <a class="text" href="{{url('/subscriptions')}}">here</a>.</h4>
            </div>
          @endif

        @foreach($questions as $question)
        <div class="card">
            <div class="card__body">
              <div class="card__side">
                <img class="card__icon" src="{{asset('art/light-bulb.png')}}" alt="">
              </div>
              <div class="card__content">
                <div class="card__header">
                  <div class="row">
                    <div class="col-md-8">
                      <h1 class="card__title">Java programming question?</h1>
                      <a class="card__subtitle" href="#">{{$question->course->course_name}}</a>
                    </div>
                    <div class="col-md-4 card__controls">
                        <!-- <div class="card__actions">
                          <a class="card__action" href="#" data-toggle="tooltip" title="Edit" data-placement="bottom">
                            <i class="fa fa-pencil"></i>
                          </a>
                          <a class="card__action" href="#" data-toggle="tooltip" title="Delete" data-placement="bottom">
                            <i class="fa fa-trash"></i>
                          </a>
                          <a class="card__action" href="#" data-toggle="tooltip" title="Report" data-placement="bottom">
                            <i class="fa fa-flag"></i>
                          </a>
                          <a class="card__action" href="#" data-toggle="tooltip" title="Bookmark" data-placement="bottom">
                            <i class="fa fa-bookmark"></i>
                          </a>
                        </div> -->
                        @if($question->asker->verified_badge >=1)
                            <a class="card__author text" href="{{url('user/'.$question->asker_id)}}"><i class="fa fa-check-circle" style="padding-right: 5px;"></i>{{$question->asker->first_name.' '.$question->asker->last_name}}</i></a>
                        @else
                            <a class="card__author text" href="{{url('user/'.$question->asker_id)}}">{{$question->asker->first_name.' '.$question->asker->last_name}}</a>
                        @endif
                    </div>
                  </div>
                </div>

                <p class="card__text">
                  {{$question->question}}
                  @if($question->attachement_path)
                    <div style="margin-top: 5px">
                      <span class="glyphicon glyphicon-paperclip"></span>
                      <a href="{{url('/user/question/download_attachement/'.$question->id)}}">{{ $question->attachement_path }}</a>
                    </div>
                  @endif
                </p>
                <p style=" font-style: italic; ">{{ date("F j, Y, g:i a",strtotime($question->created_at)) }} </p>
                @if(Auth::user())
                    <i class="fa fa-thumbs-up upvote_question" value="{{$question->id}}" title="upvote" style="color:green; font-size: 28px;"></i>
                @endif
                @if($question->votes > 0)
                    <span class="question_votes" style="color:green; font-size: 18px; padding-left: 10px; padding-right: 10px;">{{$question->votes}} </span>
                @elseif($question->votes == 0)
                    <span class="question_votes" style="font-size: 18px; padding-left: 10px; padding-right: 10px;">{{$question->votes}} </span>
                @else
                    <span class="question_votes" style="color:red; font-size: 18px; padding-left: 10px; padding-right: 10px;">{{$question->votes}} </span>
                @endif
                @if(Auth::user())
                    <i class="fa fa-thumbs-down downvote_question" value="{{$question->id}}" title="downvote" style="color:red; font-size: 28px"></i>
                @endif
              </div>
            </div>
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
                <a href="{{url('/answers/'.$question->id)}}" class="question-card__answers-count text" style="font-size: 16px">{{count($question->answers)}} Answer(s)</a>
            @elseif(Auth::user()->id != $question->asker_id)
              <span class="question-card__answers-count" style="font-size: 16px">This question has no answers yet. <a href="{{url('/answers/'.$question->id)}}" class="text">Be the first to answer!</a></span>
            @else
              <span class="question-card__answers-count" style="font-size: 16px">This question has no answers yet.</span>
            @endif

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

        <div class="col-md-4">
          <div class="buttons-list">
            <h1 class="buttons-list__title">Questions</h1>
            <ul class="buttons-list__list">
              <li class="buttons-list__item">
                <a href="{{url('/browse')}}" class="buttons-list__btn btn text">Browse Courses / Ask Question</a></a>
              </li>
            </ul>
          </div>

          <div class="buttons-list">
            <h1 class="buttons-list__title">Products Categories</h1>
            <ul class="buttons-list__list">
              @foreach($categories as $category)
              <li class="buttons-list__item">
                <a href="{{url('/user/components/'.$category->id)}}" class="buttons-list__btn btn text">{{$category->name}}<span class="buttons-list__info">{{count($category->components()->where('accepted',1)->get())}}</span></a>
              </li>
              @endforeach
              <li class="buttons-list__item">
                <a href="{{url('/add_component')}}" class="buttons-list__btn btn text">Add New Product</a>
              </li>
            </ul>
          </div>
          <div class="buttons-list">
            <h1 class="buttons-list__title">Stores</h1>
            <ul class="buttons-list__list">
              <li class="buttons-list__item">
                <a href="{{url('/user/stores')}}" class="buttons-list__btn btn text">View Stores<span class="buttons-list__info">{{$count_stores}}</span></a></a>
              </li>
            </ul>
          </div>
        </div>
  
    </div>
  </div>
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
