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


<?php $__env->startSection('content'); ?>

    <link href="<?php echo e(asset('/css/formInput.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">
    <script src="<?php echo e(asset('/js/classie.js')); ?>" type="text/javascript"></script>

    <div class="container" style="padding-left: 80px;">
        <div class="media question">
            <div style="text-align: center" class="media-left">

                <a href="<?php echo e(url('user/'.$question->asker_id)); ?>">

                    <?php if($question->asker->profile_picture): ?>
                        <img class="media-object" src="<?php echo e(asset($question->asker->profile_picture)); ?>" alt="...">

                    <?php else: ?>
                        <img class="media-object" src="<?php echo e(asset('art/default_pp.png')); ?>" alt="...">

                    <?php endif; ?>
                </a>
                <?php if(Auth::user() && count(Auth::user()->upvotesOnQuestion($question->id))): ?>
                    <i class="fa fa-thumbs-up upvote_question" value="<?php echo e($question->id); ?>" title="upvote" style="color:green; font-size: 20px;"></i>
                <?php elseif(Auth::user()): ?>
                    <i class="far fa-thumbs-up upvote_question" value="<?php echo e($question->id); ?>" title="upvote" style="color:green; font-size: 20px;"></i>
                <?php endif; ?>
                <?php if($question->votes > 0): ?>
                    <span class="question_votes" style="color:green;"><?php echo e($question->votes); ?> </span>
                <?php elseif($question->votes == 0): ?>
                    <span class="question_votes" style=""><?php echo e($question->votes); ?> </span>
                <?php else: ?>
                    <span class="question_votes" style="color:red;"><?php echo e($question->votes); ?> </span>
                <?php endif; ?>
                <?php if(Auth::user() && count(Auth::user()->downvotesOnQuestion($question->id))): ?>
                    <i class="fa fa-thumbs-down downvote_question" value="<?php echo e($question->id); ?>" title="downvote" style="color:red; font-size: 20px"></i>
                <?php elseif(Auth::user()): ?>
                    <i class="far fa-thumbs-down downvote_question" value="<?php echo e($question->id); ?>" title="downvote" style="color:red; font-size: 20px"></i>
                <?php endif; ?>
            </div>
            <div class="media-body">
                <?php if(Auth::user() && (Auth::user()->id == $question->asker_id || Auth::user()->role >= 1)): ?>
                <div class="delete_question pull-right">
                    <a onclick="return confirm('Are you sure?');" title="Delete question" class="" href="<?php echo e(url('delete_question/'.$question->id)); ?>"><span style="color:#FFAF6C" class="glyphicon glyphicon-remove"></span></a>
                </div>
                <?php endif; ?>
                    <?php if($question->asker->verified_badge >=1): ?>
                        <a href="<?php echo e(url('user/'.$question->asker_id)); ?>"><h3><?php echo e($question->asker->first_name.' '.$question->asker->last_name); ?> <span class="verified"></span></h3></a>
                    <?php else: ?>
                        <a href="<?php echo e(url('user/'.$question->asker_id)); ?>"><h3><?php echo e($question->asker->first_name.' '.$question->asker->last_name); ?> </h3></a>
                    <?php endif; ?>
                <div class="question_text">
                    <?php echo e($question->question); ?>

                </div>
                <p style="font-weight: bold; font-style: italic; "><?php echo e(date("F j, Y, g:i a",strtotime($question->created_at))); ?> </p>
            </div>

        </div>
        <h2 style=""><?php echo e(count($question->answers()->get())); ?> Answer(s)</h2>
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

            <?php foreach($answers as $answer): ?>
                <div class="media answer">
                    <div style="text-align: center" class="media-left">

                        <a href="<?php echo e(url('user/'.$answer->responder_id)); ?>">

                            <?php if($answer->responder->profile_picture): ?>
                                <img class="media-object" src="<?php echo e(asset($answer->responder->profile_picture)); ?>" alt="...">
                            <?php else: ?>
                                <img class="media-object" src="<?php echo e(asset('art/default_pp.png')); ?>" alt="...">
                            <?php endif; ?>
                        </a>
                        <?php if(Auth::user() && count(Auth::user()->upvotesOnAnswer($answer->id))): ?>
                            <i class="fa fa-thumbs-up upvote_answer" value="<?php echo e($answer->id); ?>" title="upvote" style="color:green;"></i>
                        <?php elseif(Auth::user()): ?>
                            <i class="far fa-thumbs-up upvote_answer" value="<?php echo e($answer->id); ?>" title="upvote" style="color:green;"></i>
                        <?php endif; ?>
                        <?php if($answer->votes > 0): ?>
                            <span class="answer_votes" style="color:green; font-size:15px;"><?php echo e($answer->votes); ?> </span>
                        <?php elseif($answer->votes == 0): ?>
                            <span class="answer_votes" style=""><?php echo e($answer->votes); ?> </span>
                        <?php else: ?>
                            <span class="answer_votes" style="color:red;"><?php echo e($answer->votes); ?> </span>
                        <?php endif; ?>
                        <?php if(Auth::user() && count(Auth::user()->downvotesOnAnswer($answer->id))): ?>
                            <i class="fa fa-thumbs-down downvote_answer" value="<?php echo e($answer->id); ?>" title="downvote" style="color:red;"></i>
                        <?php elseif(Auth::user()): ?>
                            <i class="far fa-thumbs-down downvote_answer" value="<?php echo e($answer->id); ?>" title="downvote" style="color:red;"></i>
                        <?php endif; ?>
                    </div>
                    <div class="media-body">
                        <?php if(Auth::user()): ?>
                            <div class="delete_answer pull-right">
                            <?php if(Auth::user()->id == $answer->responder_id): ?>
                                <a value="<?php echo e($answer); ?>" data-toggle="modal" data-target="#edit_modal" class="edit_answer" title="Edit Answer"><span class="glyphicon glyphicon-edit" style="color:#D24848;cursor:pointer;"></span></a>
                            <?php endif; ?>
                            <?php if(Auth::user()->id == $answer->responder_id || Auth::user()->role >= 1): ?>

                                    <a onclick="return confirm('Are you sure?');" title="Delete answer" href="<?php echo e(url('delete_answer/'.$answer->id)); ?>"><span style="color:#FFAF6C" class="glyphicon glyphicon-remove"></span></a>

                            <?php endif; ?>
                            <a value="<?php echo e($answer->id); ?>" data-toggle="modal" data-target="#report_modal" class="report_answer" title="Report answer"><span style="color:#D24848;cursor:pointer;" class="glyphicon glyphicon-ban-circle"></span></a>
                            </div>
                        <?php endif; ?>
                            <?php if($answer->responder->verified_badge >=1): ?>
                                <h3><?php echo e($answer->responder->first_name.' '.$answer->responder->last_name); ?><span class="verified"></span></h3>
                             <?php else: ?>
                                <h3><?php echo e($answer->responder->first_name.' '.$answer->responder->last_name); ?></h3>
                            <?php endif; ?>

                        <div class="answer_text">
                            <?php echo e($answer->answer); ?>

                            <?php if($answer->attachement_path): ?>
                                <div>
                                    <span class="glyphicon glyphicon-paperclip"></span>
                                    <a href="<?php echo e(url('/user/answer/download_attachement/'.$answer->id)); ?>"><?php echo e($answer->attachement_path); ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p style="font-weight: bold; font-style: italic; "><?php echo e(date("F j, Y, g:i a",strtotime($answer->created_at))); ?> </p>
                    </div>

                </div>
            <?php endforeach; ?> 
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

                        <button disabled="disabled" onclick="editAnswer()" class="btn btn-default">Edit</button>
                        <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
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
                        <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                    <!-- <div class="modal-footer"> -->

                    <!-- </div> -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <?php if(Auth::user()): ?>
        <form id="post_answer_form" action="" enctype="multipart/form-data" method="POST">
            <?php echo e(csrf_field()); ?>

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
        <?php else: ?>
            <div class="">You must be logged in to be able to answer. <a href="<?php echo e(url('/login')); ?>">Login here.</a></div>
        <?php endif; ?>
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
            background-image: url("<?php echo e(asset('art/ver.png')); ?>");
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
                url : "<?php echo e(url('/report_answer/')); ?>",
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
                'url' : "<?php echo e(url('')); ?>/vote/answer/"+answer_id+"/"+type,
                success: function(data){
                    location.reload();
                }
            });
        });
        $('.upvote_answer').click(function()
        {
            var answer_id = $(this).attr('value');
            var type = 0;
            var answer = $(this);
            $.ajax({
                'url' : "<?php echo e(url('')); ?>/vote/answer/"+answer_id+"/"+type,
                success: function(data){
                    location.reload();
                }
            });
        });

        $('.upvote_question').click(function () {
            var question_id = $(this).attr('value');
            var type = 0;
            var question = $(this);
            $.ajax({
                'url': "<?php echo e(url('')); ?>/vote/question/" + question_id + "/" + type,
                success: function (data) {
                    location.reload();
                }
            });
        });

        $('.downvote_question').click(function () {
            var question_id = $(this).attr('value');
            var type = 1;
            var question = $(this);
            $.ajax({
                'url': "<?php echo e(url('')); ?>/vote/question/" + question_id + "/" + type,
                success: function (data) {
                    location.reload();
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
          $('.modified_answer').val(body);
      });

      $(function() {
        $('.modified_answer').on('input', function() {
            if( $('.modified_answer').filter(function() { return !!this.value; }).length > 0 ) {
                 $('button').prop('disabled', false);
            } else {
                 $('button').prop('disabled', true);
            }
        });
      });

      function editAnswer(){
        var body = $('.modified_answer').val();
        $.ajax({
            type: "GET",
            url : "<?php echo e(url('edit_answer/')); ?>",
            data : {answer:body,answer_id:answer_id},
            success : function(data){
                location.reload();
            }
        });
    }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>