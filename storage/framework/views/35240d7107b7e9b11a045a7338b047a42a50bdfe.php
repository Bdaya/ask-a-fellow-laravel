<?php $__env->startSection('question_answer_section'); ?>
    <nav id="switch" class="center-block cl-effect-21" style="text-align:center;width: 100%; height: 70px;">
        <a id="loginSwitch" style="margin-right:3%; color: #CA6A1B;  margin-left:5%; margin-bottom:15px; border-bottom:1px solid #CA6A1B;" href="#">Questions</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="<?php echo e(url('user/'.$user->id)); ?>/answers">Answers</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="<?php echo e(url('user/'.$user->id)); ?>/calender">Calender</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="<?php echo e(url('user/'.$user->id)); ?>/bookmarks">Bookmarks</a>
    </nav>

    <h3><?php echo e($user->first_name); ?> asked <?php echo e(count($user->questions()->get())); ?> question(s).</h3>
    <br>
    <?php foreach($user->questions()->get() as $question): ?>
        <div class="media">
            <div style="text-align: center" class="media-left">
                <a href="#">
                    <?php if($user->profile_picture): ?>
                        <img class="media-object" src="<?php echo e(asset($user->profile_picture)); ?>" alt="...">
                    <?php else: ?>
                        <img class="media-object" src="<?php echo e(asset('art/default_pp.png')); ?>" alt="...">
                    <?php endif; ?>

                </a>
                <?php if($question->votes > 0): ?>
                    <span style="color:green;"><?php echo e($question->votes); ?> <span class="glyphicon glyphicon-thumbs-up"></span></span>
                <?php elseif($question->votes == 0): ?>
                    <span style=""><?php echo e($question->votes); ?> <span class="glyphicon glyphicon-thumbs-up"></span></span>
                <?php else: ?>
                    <span style="color:red;"><?php echo e($question->votes); ?> <span class="glyphicon glyphicon-thumbs-down"></span></span>
                <?php endif; ?>

            </div>
            <div class="media-body">
                <h4 class="media-heading"><?php echo e(substr($question->question,0,50).'...'); ?></h4>
                <?php echo e(substr($question->question,0,300).'....'); ?><a href="<?php echo e(url('/answers/'.$question->id)); ?>">See full question.</a>
                <p style="font-weight: bold; font-style: italic; font-size: 13px;"><?php echo e(date("F j, Y, g:i a",strtotime($question->created_at))); ?> </p>
            </div>

        </div>
    <?php endforeach; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('user.profile', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>