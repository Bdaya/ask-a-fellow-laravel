<?php $__env->startSection('content'); ?>
<div class="container" style="width: 90%">
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th style="width: 50%;">Feedback</th>
        </tr>
        <?php foreach($feedbacks as $feedback): ?>
            <tr>
                <td><?php echo e($feedback->name); ?></td>
                <td><?php echo e($feedback->email); ?></td>
                <td><?php echo e($feedback->feedback); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>