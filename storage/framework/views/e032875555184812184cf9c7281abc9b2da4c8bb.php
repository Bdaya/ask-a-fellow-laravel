<?php $__env->startSection('content'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo e(url('/css/main.css')); ?>" />
      
      <div class="container" style="text-align: center; width: 100%;">
        <div class="major_and_semester center-block">
            <ul class="buttons-list__list">
              <li class="buttons-list__item">
                <select class="buttons-list__btn btn" name="major" id="major">
                    <option value="">Select a major</option>
                    <?php foreach($majors as $major): ?>
                        <option value="<?php echo e($major->id); ?>"><?php echo e($major->major); ?></option>
                    <?php endforeach; ?>
                </select>
              </li>
              <li class="buttons-list__item">
                <select class="buttons-list__btn btn" name="semester" id="semester">
                    <option value="">Select a semester</option>
                    <?php foreach($semesters as $semester): ?>
                        <option value="<?php echo e($semester); ?>"><?php echo e($semester); ?></option>
                    <?php endforeach; ?>
                </select>
              </li>
            </ul>
            <a id="show_courses" style="margin-top:20px; margin-bottom:30px;"href="#" class="btn btn-warning"><strong>Show Courses</strong></a>
        </div>

        <div class="courses pull-right">

        </div>
    </div>

<script>
    $('#show_courses').click(function(){
        //send ajax request;
        var major = $('#major').val();
        var semester = $('#semester').val();
        var url = "<?php echo e(url('/list_courses')); ?>";
        $.ajax({
            url: url+'/'+major+'/'+semester,
            success: function(data){
                $('.courses').html(data);

            }

        });
    });
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>