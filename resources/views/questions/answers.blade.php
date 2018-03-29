<?php
    $order = 'votes';
    if(isset($_GET['sort']))
        $order = $_GET['sort'];
    $allowed = ['votes','oldest','latest'];
    if(!in_array($order,$allowed))
        $order = 'votes';


    $answers = array();
    if($order == 'votes')
        $answers = $question->answers()->orderBy('votes','desc')->orderBy('created_at','desc')->get();
    elseif($order == 'oldest')
        $answers = $question->answers()->orderBy('created_at','asc')->get();
    elseif($order == 'latest')
        $answers = $question->answers()->orderBy('created_at','desc')->get();
    else
        $answers = $question->answers()->orderBy('votes','desc')->orderBy('created_at','desc')->get();

?>

@extends('layouts.app')
@section('content')
    <link href="{{asset('/css/formInput.css')}}" rel="stylesheet">
    <script src="{{asset('/js/classie.js')}}" type="text/javascript"></script>
    <div class="container" style="padding-left: 80px;">
        <div class="media question">
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
                    <a class="downvote_question vote" value="{{$question->id}}"  title="downvote"  style="color:red"><span class="glyphicon glyphicon-thumbs-down"></span></a>
                @endif
            </div>
            <div class="media-body">
                @if(Auth::user() && (Auth::user()->id == $question->asker_id || Auth::user()->role >= 1))
                <div class="delete_question pull-right">
                    <a onclick="return confirm('Are you sure?');" title="Delete question" class="" href="{{url('delete_question/'.$question->id)}}"><span style="color:#FFAF6C" class="glyphicon glyphicon-remove"></span></a>
                </div>
                @endif
                    @if($question->asker->verified_badge >=1)
                        <a href="{{url('user/'.$question->asker_id)}}"><h3>{{$question->asker->first_name.' '.$question->asker->last_name}} <span class="verified"></span></h3></a>
                    @else
                        <a href="{{url('user/'.$question->asker_id)}}"><h3>{{$question->asker->first_name.' '.$question->asker->last_name}} </h3></a>
                    @endif
                <div class="question_text">
                    {{$question->question}}
                </div>
                <p style="font-weight: bold; font-style: italic; ">{{ date("F j, Y, g:i a",strtotime($question->created_at)) }} </p>
            </div>

        </div>
        <h2 style="">{{count($question->answers()->get())}} Answer(s)</h2>
        <form class="navbar-form" action="">
            <div class="form-group">
                <label class="form-label">Order by: </label>
                <select name="sort" class="form-control">
                    <option value="votes">Votes</option>
                    <option value="oldest">Oldest</option>
                    <option value="latest">Latest</option>
                </select>
                <input type="submit" class="btn btn-default" value="Sort">
            </div>
        </form>
        <div class="answers">

            @foreach($answers as $answer)
                <div class="media answer">
                    <div style="text-align: center" class="media-left">

                        <a href="{{url('user/'.$answer->responder_id)}}">

                            @if($answer->responder->profile_picture)
                                <img class="media-object" src="{{asset($answer->responder->profile_picture)}}" alt="...">
                            @else
                                <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="...">
                            @endif
                        </a>
                        @if(Auth::user())
                            <a class="upvote_answer vote" value="{{$answer->id}}" title="upvote" style="color:green;"><span class="glyphicon glyphicon-thumbs-up"></span></a>
                        @endif
                        @if($answer->votes > 0)
                            <span class="answer_votes" style="color:green; font-size:18px;">{{$answer->votes}} </span>
                        @elseif($answer->votes == 0)
                            <span class="answer_votes" style="">{{$answer->votes}} </span>
                        @else
                            <span class="answer_votes" style="color:red;">{{$answer->votes}} </span>
                        @endif
                        @if(Auth::user())
                            <a class="downvote_answer vote" value="{{$answer->id}}" title="downvote" style="color:red"><span class="glyphicon glyphicon-thumbs-down"></span></a>
                        @endif
                    </div>
                    <div class="media-body">
                        @if(Auth::user())
                            <div class="delete_answer pull-right">
                            @if(Auth::user()->id == $answer->responder_id)
                                <a value="{{$answer}}" data-toggle="modal" data-target="#edit_modal" class="edit_answer" title="Edit Answer"><span class="glyphicon glyphicon-edit" style="color:#D24848;cursor:pointer;"></span></a>
                            @endif
                            @if(Auth::user()->id == $answer->responder_id || Auth::user()->role >= 1)

                                    <a onclick="return confirm('Are you sure?');" title="Delete answer" href="{{url('delete_answer/'.$answer->id)}}"><span style="color:#FFAF6C" class="glyphicon glyphicon-remove"></span></a>

                            @endif
                            <a value="{{$answer->id}}" data-toggle="modal" data-target="#report_modal" class="report_answer" title="Report answer"><span style="color:#D24848;cursor:pointer;" class="glyphicon glyphicon-ban-circle"></span></a>
                            </div>
                        @endif
                            @if($answer->responder->verified_badge >=1)
                                <h3>{{$answer->responder->first_name.' '.$answer->responder->last_name}}<span class="verified"></span></h3>
                             @else
                                <h3>{{$answer->responder->first_name.' '.$answer->responder->last_name}}</h3>
                            @endif

                        <div class="answer_text">
                            {{$answer->answer}}
                            @if($answer->attachement_path)
                                <div>
                                    <span class="glyphicon glyphicon-paperclip"></span>
                                    <a href="{{url('/user/answer/download_attachement/'.$answer->id)}}">{{ $answer->attachement_path }}</a>
                                </div>
                            @endif
                        </div>
                        <p style="font-weight: bold; font-style: italic; ">{{ date("F j, Y, g:i a",strtotime($answer->created_at)) }} </p>
                    </div>

                </div>
            @endforeach 
        </div>
        
        <div id="edit_modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class=""  style="background-color:rgba(255,255,255,0.9)">

                    <button style="margin-right:15px;margin:top:10px;"type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


                    <br>
                    <div class="modal-body" style="padding: 0 50px 40px 50px;">
                        <h3>Edit Answer</h3>
                        <div class="form-group" style="width: 100%;">
                            <textarea class="form-control modified_answer"></textarea>
                        </div>

                        <button onclick="editAnswer()" class="btn btn-default">Edit</button>
                        @include('errors')
                    </div>
                    <!-- <div class="modal-footer"> -->

                    <!-- </div> -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <div id="report_modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class=""  style="background-color:rgba(255,255,255,0.9)">

                    <button style="margin-right:15px;margin:top:10px;"type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


                    <br>
                    <div class="modal-body" style="padding: 0 50px 40px 50px;">
                        <h3>Report answer </h3>
                        <div class="form-group" style="width: 50%;">
                            <input  type="radio" name="reason" value="Contains inappropriate content"> Contains inappropriate content<br>
                            <input  type="radio" name="reason" value="Contains misleading information"> Contains misleading information<br>
                            <input  type="radio" name="reason" value="Contains violent speech"> Contains violent speech<br>
                            <input  type="radio" name="reason" value="Contains hateful content"> Contains hateful content<br>
                            <input  type="radio" id="report_other" name="reason" value="Other"> Other<br>
                            <textarea class="form-control" id="report_other_text"></textarea>




                        </div>

                        <button id="submit_report" class="btn btn-default">Send</button>
                        @include('errors')
                    </div>
                    <!-- <div class="modal-footer"> -->

                    <!-- </div> -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        @if(Auth::user())
        <form id="post_answer_form" action="" enctype="multipart/form-data" method="POST">
            {{csrf_field()}}
            <div class="form-group">
                <label for="post_answer_text">Post an answer:</label>
                <textarea required class="form-control" id="post_answer_text" name="answer" placeholder="Type your answer here"></textarea>
                <br>
                  <div class="form-group">
                    <div class="col-sm-6 pull-right">
                        <input name="file" id="file" type="file">
                    </div>
                    <label for="file" class="col-sm-1.5 control-label pull-right">Attach file</label>
                  </div>
                <br>
                <div class="form-group">
                    <div class="col-sm-offset-3">
                        <button type="submit" class="btn btn-default pull-right"  id="post_answer_submit">Post Answer</button>
                    </div>
                </div>
            </div>
        </form>
        @else
            <div class="">You must be logged in to be able to answer. <a href="{{url('/login')}}">Login here.</a></div>
        @endif
    </div>



    <style>
        .question
        {
            width: 80%;
            /*min-width: 200px;*/
            margin-bottom: 50px;
        }
        .question img
        {
            width: 70px;
            height: 70px;
            border-radius: 100px;
            margin-bottom: 10px;
        }
        .question h3
        {
            /*width: 100%;*/
            font-size: 20px;
            margin-top: 2px;
            color: #621708;
            /*font-weight: bold;*/
        }

        .question .question_text
        {
            font-size: 22px;
        }

        .answer
        {
            background-color:  #FFF5E9;
            padding: 15px;
            margin-left: 80px;
            width: 60%;
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
        
        
        .vote
        {
            cursor: pointer;
        }

        #post_answer_form
        {
            width: 60%;
            margin-left: 90px;
            margin-top: 50px;
        }
        #post_answer_form textarea
        {
            resize: none;
            height:150px;
            font-size: 18px;
        }
        #post_answer_form #post_answer_submit
        {
            background-color: #FFE9CF;
            border: 1px solid #CCB69C;
            margin-top: 10px;
        }
        #post_answer_form #post_answer_submit:focus
        {
            background-color: #CCB69C;
            /*border: 1px solid #CCB69C !important;*/

        }


        @media (max-width:800px)
        {
            .question,.answer,#post_answer_form
            {
                margin-left:-30px;
                min-width: 280px;
                width: 90%;

            }

            h1,h2
            {
                font-size: 23px;
            }


            .question_text
            {
                font-size:10px;
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
        $(document).ready(function(){
            if($('#post_answer_text').val().trim().length == 0)
                $('#post_answer_submit').attr('disabled',true);
            else
                $('#post_answer_submit').attr('disabled',false);
            $('#report_other_text').hide();
        });

        $('input[name=reason]').on('change', function() {
            if($(this).val() == 'Other')
                $('#report_other_text').show();
            else
                $('#report_other_text').hide();
        });

        var reported_answer_id = 1;
        $('.report_answer').click(function(){
            reported_answer_id = $(this).attr('value');

        });
        $('#submit_report').click(function(){
            var reason = $('input[name=reason]:checked').val();
            var other = $('#report_other_text').val();
            $.ajax({
                type: "GET",
                url : "{{url('/report_answer/')}}",
                data : {reason:reason,other:other,answer_id:reported_answer_id},
                success : function(data){
                    $('#report_modal .modal-body').html(data);
                }
            });
        });
        $('.downvote_answer').click(function()
        {
            var answer_id = $(this).attr('value');
            var type = 1;
            var answer = $(this);
            $.ajax({
                'url' : "{{url('')}}/vote/answer/"+answer_id+"/"+type,
                success: function(data){
                    answer.parent().find('.answer_votes').html(data);
                }
            });
        });
        $('.upvote_answer').click(function()
        {
            var answer_id = $(this).attr('value');
            var type = 0;
            var answer = $(this);
            $.ajax({
                'url' : "{{url('')}}/vote/answer/"+answer_id+"/"+type,
                success: function(data){
                    answer.parent().find('.answer_votes').html(data);
                }
            });
        });

        $('.upvote_question').click(function () {
            var question_id = $(this).attr('value');
            var type = 0;
            var question = $(this);
            $.ajax({
                'url': "{{url('')}}/vote/question/" + question_id + "/" + type,
                success: function (data) {
                    question.parent().find('.question_votes').html(data);
                }
            });
        });

        $('.downvote_question').click(function () {
            var question_id = $(this).attr('value');
            var type = 1;
            var question = $(this);
            $.ajax({
                'url': "{{url('')}}/vote/question/" + question_id + "/" + type,
                success: function (data) {
                    console.log(data);
                    question.parent().find('.question_votes').html(data);
                }
            });
        });

        $('#post_answer_text').keyup(function()
        {
            if($(this).val().trim().length == 0)
                $('#post_answer_submit').attr('disabled',true);
            else
                $('#post_answer_submit').attr('disabled',false);

        });

        var answer_id;
      $('.edit_answer').click(function () {
          var answer = $(this).attr('value');
          answer_id = JSON.parse(answer)["id"];
          var body = JSON.parse(answer)["answer"];
          $('.modified_answer').html(body);
      });

      function editAnswer(){
        var body = $('.modified_answer').val();
        $.ajax({
            type: "GET",
            url : "{{url('edit_answer/')}}",
            data : {answer:body,answer_id:answer_id},
            success : function(data){
                location.reload();
            }
        });
    }
    </script>
@endsection