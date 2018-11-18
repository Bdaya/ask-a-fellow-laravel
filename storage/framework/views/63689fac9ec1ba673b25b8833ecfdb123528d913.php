<?php $__env->startSection('content'); ?>
<div class="container" style="width: 90%; padding-left: 50px">
    <?php if(session('mail')): ?>
        <div class="flash-message">
            <div class="alert alert-info" style="background-color: #FFAF6C; border-color: #FF6B2D; color:#AA5B0B">
                <?php echo e(session('mail')); ?>

            </div>
        </div>
    <?php endif; ?>
    <form method="POST" action="<?php echo e(url('/mail/1')); ?>">
        <?php echo e(csrf_field()); ?>

        <div class="form-group">
            <label for="mail_subject">Mail Subject</label>
            <input type="text" class="form-control" id="mail_subject" name="mail_subject" placeholder="Subject">
        </div>
        <h4 class="mail_example">Hello awesome Ask a Fellow member, </h4>
        <div class="form-group">
            <label for="mail_content">Mail Body</label>
            <textarea class="form-control" id="mail_content" name="mail_content" placeholder="Mail Body"></textarea>
        </div>
        <h4 class="mail_example">Regards,</h4>
        <h4 class="mail_example">TechHub Development Team</h4>
        <div class="form-group">
            <br>
            <label for="majors">Choose receipents</label>
            <br>
            <a id="select_all">Select all</a>
            <a id="unselect_all">Unselect all</a>
            <br>
            <?php foreach($users as $user): ?>
                <input class="recepient_check" type="checkbox" name="users[]" value="<?php echo e($user->id); ?>"> <?php echo e($user->first_name.' '.$user->last_name); ?>

                <a href="<?php echo e(url('/admin/mail/one/'.$user->id)); ?>">Email individually.</a>
                <br>
            <?php endforeach; ?>
        </div>

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
        #select_all, #unselect_all {
            cursor: pointer;
            text-decoration: none;
        }
    </style>

    <script>
        $(document).ready(function(){
            $('#unselect_all').hide();
        });

        var checked = false;
        $('#select_all').click(function(){
            var checkBoxes = $('.recepient_check');
            checkBoxes.prop("checked", !checked);
            checked = !checked;
            if(!checked)
                    $(this).text("Select all");
            else
                    $(this).text("Unselect all");
        });


    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>