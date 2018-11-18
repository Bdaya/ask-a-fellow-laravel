<?php $__env->startSection('content'); ?>
<div id = "Header">

<h1 align="center"><em><Strong>Notes Page</Strong></em></h1>
</div>
<div id ="Note">
<ul>
  <?php foreach($notes as $note): ?>
    <li>
      <h3><a href="/notes/view_note_details/<?php echo e($note->id); ?>"><?php echo e($note->title); ?></a></h3>
      <p><?php echo nl2br(e($note->description)); ?></p>
      <?php if((!empty($role))&&$role==1): ?>
          <a onclick="return confirm('Are you sure want to delete this note?');" href="/admin/delete_note/<?php echo e($note->id); ?>">Delete</a>
      <?php endif; ?>
      <!-- /browse/notes/view_note/<?php echo e($note->id); ?> -->
    </li>
  <?php endforeach; ?>
</ul>

<div style="text-align: center;">
   <?php echo e($notes->links()); ?>

</div>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>