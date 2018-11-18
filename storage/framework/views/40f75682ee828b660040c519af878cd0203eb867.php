<?php $__env->startSection('content'); ?>
    <div class="container" style="padding-left: 100px; padding-right: 100px;">
        <h1><?php echo e($user->first_name.' '.$user->last_name); ?></h1>
        <br>
        <form class="form-horizontal" style="width: 80%;" method="POST" action="" enctype="multipart/form-data">
            <?php echo e(csrf_field()); ?>

            <div class="form-group">
                <label for="first_name" class="col-sm-3 control-label">First Name</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo e($user->first_name); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class="col-sm-3 control-label">Last Name</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo e($user->last_name); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="last_name" class="col-sm-3 control-label">Major</label>
                <div class="col-sm-7">
                    <select class="form-control" name="major" id="major">
                        <option value="">Hide Major</option>
                        <?php foreach($majors as $major): ?>
                            <option value="<?php echo e($major->id); ?>" <?php echo e($user->major && $user->major->id == $major->id?'selected':''); ?>><?php echo e($major->major); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <div class="form-group">
                <label for="semester" class="col-sm-3 control-label">Semester</label>
                <div class="col-sm-7">
                    <input type="number" min="0" max="10" class="form-control" id="semester" name="semester" value="<?php echo e($user->semester); ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="bio" class="col-sm-3 control-label">About me</label>
                <div class="col-sm-7">
                   <textarea class="form-control" id="bio" name="bio"><?php echo e($user->bio); ?></textarea>
                </div>
            </div>
            <div class="form-group">
                <label for="profile_picture" class="col-sm-3 control-label">Profile Picture</label>
                <div class="col-sm-7">
                    <input name="profile_picture" id="profile_picture" type="file" accept="image/*" >
                </div>
            </div>



            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-10">
                    <button type="submit" class="btn btn-default">Update Info</button>
                </div>
            </div>
            <div class="errors" style="color:red">
            <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div>
        </form>
    </div>



<?php $__env->stopSection(); ?>




<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>