<?php $__env->startSection('content'); ?>
<div id = "Header">

<h1 align="center"><em><Strong>Notes Page</Strong></em></h1>
</div>
<div id ="Note">

  <?php foreach($notes as $note): ?>

    <div class="media question">

      <div onclick="window.location='<?php echo e(url('/notes/view_note_details/'.$note->id)); ?>'" style="text-align: center" class="media-left">
        <span class="glyphicon glyphicon-file" style="font-size: 150%"></span>
      </div>
      
      <div onclick="window.location='<?php echo e(url('/notes/view_note_details/'.$note->id)); ?>'" class="media-body">
        <h3><a href="/notes/view_note_details/<?php echo e($note->id); ?>"><?php echo e($note->title); ?></a></h3>
        <p><?php echo nl2br(e($note->description)); ?></p>
        <?php if($verified_users_courses !== null): ?>
            <a onclick="return confirm('Are you sure want to delete this note?');" href="/delete_note/<?php echo e($note->id); ?>">Delete</a>
        <?php elseif((!empty($role))&&$role==1): ?>
          <a onclick="return confirm('Are you sure want to delete this note?');" href="/admin/delete_note/<?php echo e($note->id); ?>">Delete</a>
        <?php endif; ?>
      </div>

    </div>

  <?php endforeach; ?>

<div style="text-align: center;">
   <?php echo e($notes->links()); ?>

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