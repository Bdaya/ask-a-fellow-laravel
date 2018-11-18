
<table class="table table-hover">
<?php foreach($notifications as $notification): ?>
<tr class="notification_row" href="<?php echo e($notification->notification_link); ?>">
    <td class="notification_desc">
        <?php echo e($notification->notification_description); ?>

    </td>
    <td class="notification_date">
        <?php echo e(date("F j, Y, g:i a",strtotime($notification->created_at))); ?>

    </td>
</tr>
<?php endforeach; ?>

</table>
<a href="<?php echo e(url('/notifications')); ?>">View all notifications</a>
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
    $('.notification_row').click(function(){
       window.location.href = $(this).attr('href');
    });
</script>