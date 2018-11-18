;

<?php $__env->startSection('content'); ?>
    <table class="table table-hover center-block" style="width:70%">
        <?php foreach($notifications as $notification): ?>
            <tr class="notification_row" href="<?php echo e($notification->notification_link); ?>">
                <td class="notification_desc">
                    <?php echo e($notification->notification_description); ?>

                </td>
                <td class="notification_date">
                    <?php echo e(date("F j, Y, g:i a",strtotime($notification->created_at))); ?>

                </td>
                <td style="width: 150px;">
                    <?php if($notification->seen): ?>
                        <a class="mark_as_unread" href="#" value="<?php echo e($notification->id); ?>">Mark as unread</a>
                    <?php else: ?>
                        <a class="mark_as_read" href="#" value="<?php echo e($notification->id); ?>">Mark as read</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>

    <style>
        .notification_row{
            cursor: pointer;
        }
        .notification_row:hover
        {
            background-color: rgba(0,0,0,0.05) !important;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('.mark_as_read').hide();
            $('.mark_as_unread').hide();
        });
        $('.notification_desc').click(function(){
            window.location.href = $(this).parent().attr('href');
        });
        $('.notification_date').click(function(){
            window.location.href = $(this).parent().attr('href');
        });
        $(document).on('click','.mark_as_unread',function(){
            var notification_id = $(this).attr('value');
            var notification = $(this);
            $.ajax({
                'url' : "<?php echo e(url('/mark_notification')); ?>"+"/"+notification_id+"/0",
                'success' : function(data)
                {
                   notification.parent().html(data);
                }
            });
        });

        $(document).on('click','.mark_as_read',function(){
            var notification_id = $(this).attr('value');
            var notification = $(this);
            $.ajax({
                'url' : "<?php echo e(url('/mark_notification')); ?>"+"/"+notification_id+"/1",
                'success' : function(data)
                {
                    notification.parent().html(data);
                }
            });
        });


        $(document).on('mouseenter','.notification_row',function(){
            $(this).find('.mark_as_unread').show();
            $(this).find('.mark_as_read').show();
        });
        $(document).on('mouseleave','.notification_row',function(){
            $(this).find('.mark_as_unread').hide();
            $(this).find('.mark_as_read').hide();
        });

    </script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>