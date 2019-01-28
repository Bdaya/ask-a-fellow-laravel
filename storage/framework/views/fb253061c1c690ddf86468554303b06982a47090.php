<?php

//die($questions_ordered);
$page = 0;
if (isset($_GET['page']) && $_GET['page'] > 0)
    $page = $_GET['page'];
$take = 10;
if (isset($_GET['take']) && $_GET['take'] > 0)
    $take = $_GET['take'];

$pages = ceil($count_questions / $take);

$sort = 'latest';
if (isset($_GET['sort']))
    $sort = $_GET['sort'];


?>


<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <div class="container" style="width:100%;">
        <div class="questions">
            <h3 style="margin-left: 50px">Showing <?php echo e(count($questions_ordered)); ?> out of <?php echo e($count_questions); ?>

                Question(s).</h3>

            <div id="filtration_form">
                <form class="" action="">
                    <div class="form-group">
                        <label class="form-label">Order by: </label>
                        <select name="sort" class="form-control">
                            <option <?php echo e(isset($sort) && $sort == 'votes'?'selected':''); ?> value="votes">Votes</option>
                            <option <?php echo e(isset($sort) && $sort == 'answers'?'selected':''); ?> value="answers">Number of
                                answers
                            </option>
                            <option <?php echo e(isset($sort) && $sort == 'oldest'?'selected':''); ?> value="oldest">Oldest</option>
                            <option <?php echo e(isset($sort) && $sort == 'latest'?'selected':''); ?> value="latest">Latest</option>
                        </select>
                        <label class="form-label">Questions per page: </label>
                        <input class="form-control" type="number" name="take"
                               value="<?php echo e(isset($_GET['take'])?$_GET['take']:10); ?>">
                        <input type="submit" class="btn btn-default" value="Update">
                    </div>
                </form>
            </div>


            <nav class="center-block" style="text-align: center">
                <ul class="pagination">
                    <?php if($page > 0): ?>
                        <li><a href="?page=<?php echo e($page - 1); ?>&take=<?php echo e($take); ?>" aria-label="Previous"><span aria-hidden="true">«</span></a>
                        </li>
                    <?php endif; ?>
                    <?php for($i = 0; $i < $pages; $i++): ?>
                        <?php if($page == $i): ?>
                            <li class="active"><a href="?page=<?php echo e($i); ?>&take=<?php echo e($take); ?>&sort=<?php echo e($sort); ?>"><?php echo e($i + 1); ?> <span
                                            class="sr-only">(current)</span></a></li>
                        <?php else: ?>
                            <li><a href="?page=<?php echo e($i); ?>&take=<?php echo e($take); ?>&sort=<?php echo e($sort); ?>"><?php echo e($i + 1); ?></a></li>
                        <?php endif; ?>

                    <?php endfor; ?>
                    <?php if($page < $pages - 1): ?>
                        <li class="<?php echo e($page >= $pages-1? 'disabled':''); ?>"><a href="#" aria-label="Next"><span
                                        aria-hidden="true">»</span></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php if(Session::has('bookmark')): ?>
              <div class="alert alert-info" style="width: 75%"><?php echo e(Session::get('bookmark')); ?></div>
            <?php endif; ?>
            <?php foreach($questions_ordered as $question): ?>
                <div href="<?php echo e(url('answers/'.$question->id)); ?>" class="media question">
                    <div style="text-align: center" class="media-left">

                        <a href="<?php echo e(url('user/'.$question->asker_id)); ?>">

                            <?php if($question->asker->profile_picture): ?>
                                <img class="media-object" src="<?php echo e(asset($question->asker->profile_picture)); ?>" alt="...">

                            <?php else: ?>
                                <img class="media-object" src="<?php echo e(asset('art/default_pp.png')); ?>" alt="...">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="media-body" style="cursor: pointer;">
                        <?php if(Auth::user()): ?>
                            <div class="delete_question pull-right">
                                <a title="Bookmark Question" href="<?php echo e(url('user/bookmark/'.$question->id)); ?>"><span style="color:#FFAF6C" class="glyphicon glyphicon-bookmark"></span></a>
                                <?php if(Auth::user()->id == $question->asker_id): ?>
                                    <a value="<?php echo e($question); ?>" data-toggle="modal" data-target="#edit_modal" class="edit_question" title="Edit Question"><span class="glyphicon glyphicon-edit" style="color:#D24848;cursor:pointer;"></span></a>
                                <?php endif; ?>
                                <?php if(Auth::user()->id == $question->asker_id || Auth::user()->role >= 1 || $verified_users_courses !== null): ?>

                                    <a onclick="return confirm('Are you sure?');" title="Delete question"
                                       href="<?php echo e(url('delete_question/'.$question->id.'/'.$verified_users_courses)); ?>"><span style="color:#FFAF6C" class="glyphicon glyphicon-remove"></span></a>

                                <?php endif; ?>
                                <a value="<?php echo e($question->id); ?>" data-toggle="modal" data-target="#report_modal"
                                   class="report_question" title="Report question"><span style="color:#D24848;" class="glyphicon glyphicon-ban-circle"></span></a>
                            </div>
                        <?php endif; ?>
                            <?php if($question->asker->verified_badge >=1): ?>
                                <h3><?php echo e($question->asker->first_name.' '.$question->asker->last_name); ?> <span class="verified"></span></h3>
                            <?php else: ?>
                                <h3><?php echo e($question->asker->first_name.' '.$question->asker->last_name); ?> </h3>
                            <?php endif; ?>
                        <?php if(isset($all)): ?>
                            <h5 style="color:green"><?php echo e($question->course->course_code); ?></h5>
                        <?php endif; ?>
                        <div class="question_text">
                            <?php echo e($question->question); ?>

                             <?php if($question->attachement_path): ?>
                            <div>
                              <span class="glyphicon glyphicon-paperclip"></span>
                              <a href="<?php echo e(url('/user/question/download_attachement/'.$question->id)); ?>"><?php echo e($question->attachement_path); ?></a>
                            </div>
                          <?php endif; ?>
                        </div>
                        <p style="font-weight: bold; font-style: italic; "><?php echo e(date("F j, Y, g:i a",strtotime($question->created_at))); ?> </p>
                    </div>
                     <div>
                        <?php if(Auth::user() && count(Auth::user()->upvotesOnQuestion($question->id))): ?>
                            <i class="fa fa-thumbs-up upvote_question" value="<?php echo e($question->id); ?>" title="upvote" style="color:green; font-size: 20px; cursor: pointer;"></i>
                        <?php elseif(Auth::user()): ?>
                            <i class="far fa-thumbs-up upvote_question" value="<?php echo e($question->id); ?>" title="upvote" style="color:green; font-size: 20px; cursor: pointer;"></i>
                        <?php endif; ?>
                        <?php if($question->votes > 0): ?>
                            <span class="question_votes" style="color:green;"><?php echo e($question->votes); ?> </span>
                        <?php elseif($question->votes == 0): ?>
                            <span class="question_votes" style=""><?php echo e($question->votes); ?> </span>
                        <?php else: ?>
                            <span class="question_votes" style="color:red;"><?php echo e($question->votes); ?> </span>
                        <?php endif; ?>
                        <?php if(Auth::user() && count(Auth::user()->downvotesOnQuestion($question->id))): ?>
                            <i class="fa fa-thumbs-down downvote_question" value="<?php echo e($question->id); ?>" title="downvote" style="color:red; font-size: 20px; cursor: pointer;"></i>
                        <?php elseif(Auth::user()): ?>
                            <i class="far fa-thumbs-down downvote_question" value="<?php echo e($question->id); ?>" title="downvote" style="color:red; font-size: 20px; cursor: pointer;"></i>
                        <?php endif; ?>
                    </div>
                </div>

            <?php endforeach; ?>

            <div id="edit_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class=""  style="background-color:rgba(255,255,255,0.9)">

                        <button style="margin-right:15px;margin:top:10px;"type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

                        <br>
                        <div class="modal-body" style="padding: 0 50px 40px 50px;">
                            <h3>Edit Question</h3>
                            <div class="form-group" style="width: 100%;">
                                <textarea class="form-control modified_question"></textarea>
                            </div>

                            <button disabled="disabled" onclick="editQuestion()" class="btn btn-default">Edit</button>
                            <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>
                        <!-- <div class="modal-footer"> -->

                        <!-- </div> -->
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>

            <div id="report_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="" style="background-color:rgba(255,255,255,0.9)">

                        <button style="margin-right:15px;margin:top:10px;" type="button" class="close"
                                data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


                        <br>
                        <div class="modal-body" style="padding: 0 50px 40px 50px;">
                            <h3>Report question </h3>
                            <div class="form-group" style="width: 50%;">
                                <input type="radio" name="reason" value="Contains inappropriate content"> Contains
                                inappropriate content<br>
                                <input type="radio" name="reason" value="Contains misleading information"> Contains
                                misleading information<br>
                                <input type="radio" name="reason" value="Contains violent speech"> Contains violent
                                speech<br>
                                <input type="radio" name="reason" value="Contains hateful content"> Contains hateful
                                content<br>
                                <input type="radio" id="report_other" name="reason" value="Other"> Other<br>
                                <textarea class="form-control" id="report_other_text"></textarea>
                                <input type="hidden" id="reported_question_id" value="">


                            </div>

                            <button id="submit_report" class="btn btn-default">Send</button>
                            <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>
                        <!-- <div class="modal-footer"> -->

                        <!-- </div> -->
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>

            <form id="post_question_form" action="" enctype="multipart/form-data" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="form-group">
                    <label for="post_question_text">Ask a question:</label>
                    <textarea required class="form-control" id="post_question_text" name="question"
                              placeholder="Type your question here"></textarea>
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
                            <button type="submit" class="btn btn-default pull-right" id="post_question_submit">Post Question</button>
                        </div>
                    </div>
                    <?php if(isset($all)): ?>
                        <div class="form-group" style="width:50%;">
                            <label for="course" style="">Post question to: </label>
                            <select id="course" class="form-control" name="course">
                                <?php foreach($courses as $course): ?>
                                    <option value="<?php echo e($course->id); ?>"><?php echo e($course->course_name); ?></option>

                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>
            </form>

        </div>

    </div>



    <style>
        #filtration_form {
            width: 20%;
            float: right;
            /*display: inline-block;*/

        }

        .question {
            background-color: #FFF5E9;
            padding: 15px;
            margin-left: 80px;
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
        .media img+span.badge {
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
        $(document).ready(function () {
            $('#report_other_text').hide();
        });


        $('input[name=reason]').on('change', function () {
            if ($(this).val() == 'Other')
                $('#report_other_text').show();
            else
                $('#report_other_text').hide();
        });

        var reported_question_id = 1;
        $('.report_question').click(function () {
            reported_question_id = $(this).attr('value');

        });
        $('#submit_report').click(function () {
            var reason = $('input[name=reason]:checked').val();
            var other = $('#report_other_text').val();
            $.ajax({
                type: "GET",
                url: "<?php echo e(url('/report_question/')); ?>",
                data: {reason: reason, other: other, question_id: reported_question_id},
                success: function (data) {
                    $('#report_modal .modal-body').html(data);
                }
            });
        });

        $('.question_text').click(function () {
            window.location.href = $(this).parent().parent().attr('href');
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

        $(function() {
            $('.modified_question').on('input', function() {
                if( $('.modified_question').filter(function() { return !!this.value; }).length > 0 ) {
                     $('button').prop('disabled', false);
                } else {
                     $('button').prop('disabled', true);
                }
            });
          });

      var question_id;
      $('.edit_question').click(function () {
          var question = $(this).attr('value');
          question_id = JSON.parse(question)["id"];
          var body = JSON.parse(question)["question"];
          $('.modified_question').val(body);
      });

      function editQuestion(){
        var body = $('.modified_question').val();
        $.ajax({
            type: "GET",
            url : "<?php echo e(url('edit_question/')); ?>",
            data : {question:body,question_id:question_id},
            success : function(data){
                location.reload();
            }
        });
      }

    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>