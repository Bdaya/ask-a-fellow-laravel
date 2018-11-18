;
<?php $__env->startSection('content'); ?>

    <div class=info_section>
        <?php if(session('updated')): ?>
            <div class="flash-message">
                <div class="alert alert-info" style="background-color: #FFAF6C; border-color: #FF6B2D; color:#AA5B0B">
                    <?php echo e(session('updated')); ?>

                </div>
            </div>
        <?php endif; ?>
        <?php if(session('mail')): ?>
            <div class="flash-message">
                <div class="alert alert-info" style="background-color: #FFAF6C; border-color: #FF6B2D; color:#AA5B0B">
                    <?php echo e(session('mail')); ?>

                </div>
            </div>
        <?php endif; ?>
        <div class="profile_picture">
            <?php if($user->profile_picture): ?>
                <img src="<?php echo e(asset($user->profile_picture)); ?>" style="">
            <?php else: ?>
                <img src="<?php echo e(asset('art/default_pp.png')); ?>" style="">
            <?php endif; ?>

        </div>
            <?php if($user->verified_badge >=1): ?>
                <h1><?php echo e($user->first_name.' '.$user->last_name); ?>  <span class="verified"></span></h1>
            <?php else: ?>
                <h1><?php echo e($user->first_name.' '.$user->last_name); ?>  </h1>
            <?php endif; ?>

        <a href="#" style="color:#0057A2"><?php echo e($user->email); ?></a>
        <p style="font-size: 20px;"><?php echo e($user->semester?'Semester '.$user->semester:''); ?><br> <?php echo e($user->major?$user->major->major:''); ?></p>
        <p><?php echo e($user->bio); ?></p>
        <?php if(Auth::user() && Auth::user()->id == $user->id): ?>
                <a class="btn btn-success " href="<?php echo e(url('user/update')); ?>">Update info</a>
        <?php endif; ?>
        <?php if(Auth::user() && Auth::user()->role >= 1 && Auth::user()->id != $user->id): ?>
                <a class="btn btn-info " href="<?php echo e(url('admin/mail/one/'.$user->id)); ?>">Email user</a>
        <?php endif; ?>

    </div>
    <div class="questions_answers">

       <?php echo $__env->yieldContent('question_answer_section'); ?>


    </div>


    <link href="<?php echo e(asset('css/textAnimation.css')); ?>" rel="stylesheet">
    <style>
        /* Profile styling */

        @font-face {
            font-family: ubuntu;
            src: url('<?php echo e(asset('fonts/ubuntu.ttf')); ?>');
        }
        #main_content{

            padding-top: 0px !important;
            padding-bottom: 50px !important;
            /*height: 800px;*/
            width: 80% !important;
            /*background-color: #F7E8D6;*/
            margin-top: 30px !important;
            margin-bottom: 10px ;
            /*box-shadow: 0px 5px 10px -1px #888888;*/
            z-index: 1;
        }

        .info_section
        {
            /*height: 400px;*/
            background-color: #FF953D;
            text-align: center;
            padding: 40px;
        }
        .profile_picture
        {




        }
        .profile_picture img{
            /*position: relative;*/
            /*display: inline-block;*/
            width: 200px;
            height: 200px;
            border-radius: 100px;
            object-fit: cover;
        }
        .info_section h1
        {
            font-size: 40px;
            color: #621708;
            font-family: 'ubuntu', sans-serif;
        }
        .info_section p{

        }

        .questions_answers{
            padding: 50px;
            padding-left: 100px;
            padding-right: 100px;
            margin-top: 20px;
        }
        .questions_answers .media
        {
            width: 70%;
            min-width: 200px;
            margin-bottom: 50px;
        }
        .questions_answers .media img
        {
            width: 70px;
            height: 70px;
            border-radius: 100px;
            margin-bottom: 10px;
        }
        .questions_answers .media h4
        {
            /*width: 100%;*/
            /*font-size: 17px;*/
            font-weight: bold;
        }



        @media (max-width: 600px) {
            .profile_picture img{
                width: 100px;
                height: 100px;
            }
            .info_section
            {
                font-size: 14px;
            }
            .info_section h1
            {
                font-size: 25px;
            }
            .questions_answers{

                padding-left: 20px;

            }
            .questions_answers .media
            {
                width: 100%;
                font-size: 13px;
            }
            .questions_answers .media h4
            {
                /*width: 100%;*/
                font-size: 17px;
                font-weight: bold;
            }

            #switch a
            {
                font-size: 13px !important;
            }
            #main_content h3
            {
                font-size: 18px;
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>