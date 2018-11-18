

<?php if($errors): ?>
    <?php foreach($errors->all() as $error): ?>
        <li><?php echo e($error); ?></li>
    <?php endforeach; ?>

<?php endif; ?>
