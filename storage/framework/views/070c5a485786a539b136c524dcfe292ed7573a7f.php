<?php $__env->startSection('content'); ?>
<div class="container">
    <h1>All events</h1>

    <?php foreach($events as $event): ?>

        <div href="<?php echo e(url('events/'.$event->id)); ?>" class="media question">
            <div style="text-align: center" class="media-left">

                <a href="<?php echo e(url('user/'.$event->creator_id)); ?>">
                    <?php if($event->creator->profile_picture): ?>
                        <img class="media-object" src="<?php echo e(asset($event->creator->profile_picture)); ?>" alt="...">

                    <?php else: ?>
                        <img class="media-object" src="<?php echo e(asset('art/default_pp.png')); ?>" alt="...">
                    <?php endif; ?>
                </a>


            </div>
            <div class="media-body" style="cursor: pointer;">
                <?php if(Auth::user()): ?>
                    <div class="delete_question pull-right">
                        <?php if(Auth::user()->id == $event->creator_id || Auth::user()->role >= 1): ?>

                            <a onclick="return confirm('Are you sure want to delete this event?');" title="Delete event"
                               href="<?php echo e(url('delete_event/'.$event->id)); ?>"><span style="color:#FFAF6C"
                                                                                      class="glyphicon glyphicon-remove"></span></a>

                        <?php endif; ?>

                    </div>
                <?php endif; ?>
                <?php if($event->creator->verified_badge >=1): ?>
                    <h3><?php echo e($event->creator->first_name.' '.$event->creator->last_name); ?> <span class="verified"></span></h3>
                <?php else: ?>
                    <h3><?php echo e($event->creator->first_name.' '.$event->creator->last_name); ?> </h3>
                <?php endif; ?>
                <div class="question_text">
                   <h3><?php echo e($event->title); ?></h3>
                    <p class="pull-right"><?php echo e($event->place); ?></p>
                    <h5><?php echo e($event->description); ?></h5>
                </div>
                <p style="font-weight: bold; font-style: italic; "><?php echo e(date("F j, Y, g:i a",strtotime($event->date))); ?> </p>
            </div>


        </div>
    <?php endforeach; ?>

   <div style="text-align: center;">
       <?php echo e($events->links()); ?>

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
    $('.question_text').click(function () {
        window.location.href = $(this).parent().parent().attr('href');
    });
</script>

<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>