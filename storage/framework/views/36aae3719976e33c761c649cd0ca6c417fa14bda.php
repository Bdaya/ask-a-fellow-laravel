<?php $__env->startSection('content'); ?>

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

              <div class="panel-heading">
              <h1>Event Requests</h1>
              </div>

              <div class="panel-body">
               <?php foreach($requests as $request): ?>
               <ul>
               <h3></h3>
                <a href="/admin/request/<?php echo e($request->id); ?>"><?php echo e($request->title); ?></a>
                <br>
                </ul>
               <?php endforeach; ?>
              </div>
          	
        </div>
    </div>
</div>

<?php if(Session::has('error')): ?>
    <div class="alert alert-danger"><?php echo e(Session::get('error')); ?></div>
  <?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>