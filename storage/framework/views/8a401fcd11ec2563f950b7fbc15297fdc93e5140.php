;

<?php $__env->startSection('content'); ?>
  <h1 style="text-align:center"> Notes Upload Requests </h1>
   <div class="container" >
   <?php if(!$notes_upload): ?>
    <h3 style="text-align: center; margin-top: 50px; margin-bottom: 50px"> No note upload requests. </h3>
    <?php endif; ?>
    <?php foreach($notes_upload as $note): ?>
        <div class="row">
          <div class="col-md-6">
           <h3> <?php echo e($note->title); ?> </h3>  <br>
          <b>Description: </b>  <?php echo e($note->description); ?> <br>
          <b>Uploaded by: </b>  <?php echo e($note->first_name); ?>  <?php echo e($note->last_name); ?> <br>
          <b>Course: </b><?php echo e($note->course_name); ?> <?php echo e($note->course_code); ?> <br>



          </div>

          <div style="margin-top: 50px" class="col-md-3 button-group">
            <a href="/admin/approve_note/<?php echo e($note->id); ?>" class="upload btn btn-success" id="<?php echo e($note->id); ?>">Upload</a>
            <a href="/admin/delete_note/<?php echo e($note->id); ?>" class="delete btn btn-danger" id="<?php echo e($note->id); ?>">Delete</a>
            <a href="/admin/view_note/<?php echo e($note->id); ?>" class="delete btn btn-primary" id="<?php echo e($note->id); ?>" target="_blank">View</a>
          </div>
        </div>

    <?php endforeach; ?>
   </div>
   <hr>
  <h1 style="text-align:center"> Notes Delete Requests </h1>
  <?php if(!$notes_delete): ?>
    <h3 style="text-align: center; margin-top: 50px; margin-bottom: 50px"> No note delete requests. </h3>
    <?php endif; ?>
   <div class="container" >
    <?php foreach($notes_delete as $note): ?>
        <div class="row">
          <div class="col-md-6">
           <h3> <?php echo e($note->title); ?> </h3>  <br>
          <b>Description: </b>  <?php echo e($note->description); ?> <br>
          <b>Uploaded by: </b>  <?php echo e($note->first_name); ?>  <?php echo e($note->last_name); ?> <br>
          <b>Course: </b><?php echo e($note->course_name); ?> <?php echo e($note->course_code); ?> <br>
          <b>Delete comment:</b><?php echo e($note->comment_on_delete); ?> <br>


          </div>

          <div style="margin-top: 50px" class="col-md-3 button-group">
            <a href="/admin/delete_note/<?php echo e($note->id); ?>" class="delete btn btn-success" id="<?php echo e($note->id); ?>">Delete</a>
            <a href="/admin/reject_note_delete/<?php echo e($note->id); ?>" class="delete btn btn-danger" id="<?php echo e($note->id); ?>">Reject</a>
            <a href="/admin/view_note/<?php echo e($note->id); ?>" class="delete btn btn-primary" id="<?php echo e($note->id); ?>" target="_blank">View</a>
          </div>
        </div>

    <?php endforeach; ?>
   </div>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>