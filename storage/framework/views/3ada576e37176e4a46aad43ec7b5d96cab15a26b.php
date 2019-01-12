<?php $__env->startSection('content'); ?>

<div class="container" style="padding-left: 100px; padding-right: 100px;">
        <h1>Upload Notes</h1>
        <br>
        <form class="form-horizontal" style="width: 80%;" method="POST" action="" enctype="multipart/form-data">
            <?php echo e(csrf_field()); ?>

            <div class="form-group">
                <label for="title" class="col-sm-3 control-label">Title</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="Title" name="title" value="<?php echo e(old('title')); ?>" placeholder="Note Title">
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-sm-3 control-label">Description</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="description" name="description" value="<?php echo e(old('description')); ?>" placeholder="Note Description">
                </div>
            </div>
            <div class="form-group">
                <label for="file" class="col-sm-3 control-label">File</label>
                <div class="col-sm-7">
                    <input name="file" id="file" type="file">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-10">
                    <button type="submit" class="btn btn-default">Upload</button>
                </div>
            </div>
            <div class="errors" style="color:red">
            <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div>
            <br>
            <?php if(Session::has('success')): ?>
                <div class="alert alert-info"><?php echo e(Session::get('success')); ?></div>
            <?php endif; ?>
        </form>
    </div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>