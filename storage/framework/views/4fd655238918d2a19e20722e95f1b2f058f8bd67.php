<?php $__env->startSection('content'); ?>
    <div class="" style="padding: 50px;">
        <h1>Number of questions: <?php echo e($questions); ?></h1>
        <h1>Number of answers: <?php echo e($answers); ?></h1>
        <h1>Number of users: <?php echo e($users); ?></h1>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>