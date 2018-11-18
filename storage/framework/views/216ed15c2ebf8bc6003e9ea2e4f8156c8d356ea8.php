<?php $__env->startSection('question_answer_section'); ?>
    <nav id="switch" class="center-block cl-effect-21" style="text-align:center;width: 100%; height: 70px;">
        <a id="loginSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="#">Questions</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="<?php echo e(url('user/'.$user->id)); ?>/answers">Answers</a>
        <a id="registerSwitch" style="    margin-right:3%; color: #CA6A1B;  margin-left:5%; margin-bottom:15px; border-bottom:1px solid #CA6A1B;" href="<?php echo e(url('user/'.$user->id)); ?>/calender">Calender</a>
        <a id="registerSwitch" style="opacity:0.5;margin-left:3%;color: #CA6A1B;  margin-right:5%; border-bottom:1px solid #CA6A1B;" href="<?php echo e(url('user/'.$user->id)); ?>/bookmarks">Bookmarks</a>
    </nav>

    <?php if($calender == null): ?>
        <div style="text-align: center;">
            <h4>You don't have a calender yet, click the button to create one</h4>
            <form action="<?php echo e(url('calender/create')); ?>" method="post">
                <input class="btn btn-default submit"
                       style="background-color:#FF953D; border: 1pxx solid #CC953D;" href="#"
                       type="submit" value="create" placeholder="create">
            </form>
        </div>

    <?php else: ?>

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

                                <a onclick="return confirm('Are you sure?');" title="Delete event"
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
    <?php endif; ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('user.profile', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>