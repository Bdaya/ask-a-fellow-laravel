<?php $__env->startSection('content'); ?>

    <div class="container" style="width: 90%; padding-left: 50px">
        <?php if(session('mail')): ?>
            <div class="flash-message">
                <div class="alert alert-info" style="background-color: #FFAF6C; border-color: #FF6B2D; color:#AA5B0B">
                    <?php echo e(session('mail')); ?>

                </div>
            </div>
        <?php endif; ?>
        <h2>Send mail to <?php echo e($user->first_name.' '.$user->last_name); ?></h2>
        <br>
        <form method="POST" action="<?php echo e(url('/mail/0')); ?>">
            <?php echo e(csrf_field()); ?>

            <input type="hidden" name="user_id" value="<?php echo e($user->id); ?>">
            <div class="form-group">
                <label for="mail_subject">Mail Subject</label>
                <input type="text" class="form-control" id="mail_subject" name="mail_subject" placeholder="Subject">
            </div>
            <h4 class="mail_example">Hello <?php echo e($user->first_name); ?>, </h4>
            <div class="form-group">
                <label for="mail_content">Mail Body</label>
                <textarea class="form-control" id="mail_content" name="mail_content" placeholder="Mail Body"></textarea>
            </div>
            <h4 class="mail_example">Regards,</h4>
            <h4 class="mail_example">TechHub Development Team</h4>


            <button type="submit" class="btn btn-default">Send Mail</button>

            <div class="error" style="color:red">
                <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div>

        </form>
    </div>

    <style>
        .mail_example {
            font-style: italic;
            color:#0000ff;
        }
    </style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>