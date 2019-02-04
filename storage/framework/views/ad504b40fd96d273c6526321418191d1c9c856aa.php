<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Verify Your Email Address</h2>

<div>
    Hi <?php echo e($name); ?>,
    <br>
    Thanks for creating an account on Ask a Fellow.<br>
    Please follow the link below to verify your email address
    <a href="<?php echo e(url('/register/verify/' . $confirmation_code)); ?>"><?php echo e(url('/register/verify/' . $confirmation_code)); ?>.<br/></a>

    <br>
    Have a fruitful experience!
    <br>
    Regards,<br>
    AskaFellow Administration Team
</div>

</body>
</html>