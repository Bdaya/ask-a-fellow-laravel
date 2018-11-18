<?php

//die($questions_ordered);
$page = 0;
if(isset($_GET['page']) && $_GET['page'] > 0)
    $page = $_GET['page'];
$take = 10;



$pages = ceil(count($users->get())/$take);
$users = $users->skip($page * $take)->take($take)->get();



?>



<?php $__env->startSection('content'); ?>
<div class="container" style="width: 90%; padding-left: 50px; ">
    <?php foreach($users as $user): ?>
        <div class="media center-block" style="max-width: 300px;">
            <div class="media-left">
                <a href="#">
                    <?php if($user->profile_picture): ?>
                        <img class="pp" src="<?php echo e(asset($user->profile_picture)); ?>" >
                    <?php else: ?>
                        <img class="pp" src="<?php echo e(asset('art/default_pp.png')); ?>" >
                    <?php endif; ?>
                </a>
            </div>
            <div class="media-body">
                <h4 class="media-heading"><a href="<?php echo e(url('user/'.$user->id)); ?>"><?php echo e($user->first_name.' '.$user->last_name); ?></a>
                    <?php if(!$user->confirmed): ?>
                        <small style="color:red">Not verified!</small>
                    <?php endif; ?>

                </h4>
                <a href="<?php echo e(url('admin/mail/one/'.$user->id)); ?>">Send mail</a>

            </div>
        </div>
    <?php endforeach; ?>


    <nav class="center-block" style="text-align: center">
        <ul class="pagination">
            <?php if($page > 0): ?>
                <li><a href="?page=<?php echo e($page - 1); ?>" aria-label="Previous"><span aria-hidden="true">«</span></a> </li>
            <?php endif; ?>
            <?php for($i = 0; $i < $pages; $i++): ?>
                <?php if($page == $i): ?>
                    <li class="active"><a href="?page=<?php echo e($i); ?>"><?php echo e($i + 1); ?> <span class="sr-only">(current)</span></a></li>
                <?php else: ?>
                    <li><a href="?page=<?php echo e($i); ?>"><?php echo e($i + 1); ?></a></li>
                <?php endif; ?>

            <?php endfor; ?>
            <?php if($page < $pages - 1): ?>
                <li class="<?php echo e($page >= $pages-1? 'disabled':''); ?>"><a href="?page=<?php echo e($page+1); ?>" aria-label="Next"><span aria-hidden="true">»</span></a></li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

    <style>
        .pp {
            width: 50px;
            height:auto;
            border-radius: 30px;
        }

        .pagination a
        {
            color: #E66900 !important;
            background-color: #FDF9F3 !important;
        }
        .pagination .active a
        {
            background-color: #FFAF6C !important;
            border-color: #CC8C39;
            color: #BD5D0D !important;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>