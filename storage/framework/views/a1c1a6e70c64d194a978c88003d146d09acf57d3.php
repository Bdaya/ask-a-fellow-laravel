<?php $__env->startSection('content'); ?>
    <div class ="container" style="padding-left: 50px;">

        <div class="logo-about">
            <img src="<?php echo e(asset('art/logo.png')); ?>">
        </div>
        <h1>Ask a Fellow</h1>


        <div class="about-text">
        <p>Ask a fellow is an educational social network connecting students from the GUC.
            You can ask questions, get answers from your colleagues or TAs.</p>
            <p>
            Customize your news feed by selecting only the courses you wish to get news from.
                </p>
            <p>
            You can choose to order the answers according to votes, to ensure that you will not miss the most credible and the most accurate answer.
                </p>
            <p>
            You can choose to order the questions according to the upvotes, most recent or the number of answers.
                </p>
            <p>
            With the customized subscription method, you can be sure that no question in your selected courses will be missed.
                </p>
            <p>
            The reporting system is developed to minify as much as possible the spammy-like posts.
                </p>
            Enter your personal information, and share your knowledge with your own identity.
            No more Facebook study groups hassle, welcome to your learning environment</p>
        </div>
    </div>

    <style>

        .about-text {
            width: 50%;
        }

        .logo-about{
            float:right;
            width: 40%;
            margin-right: 50px;
        }
        .logo-about img {
            width: 90%;
            height: auto;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>