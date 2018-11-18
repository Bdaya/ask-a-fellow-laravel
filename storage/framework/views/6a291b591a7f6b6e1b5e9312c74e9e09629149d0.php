;
<?php $__env->startSection('content'); ?>
    <div class="container" style="padding-left:100px; width:100%;">
        <h1>Manage subscriptions</h1>
        <form method="POST" action="">
            <?php echo e(csrf_field()); ?>

            <ul>

            <?php foreach($majors as $major): ?>
                <li><h4><a class="select_major" data-toggle="collapse" href="#semesters_<?php echo e($major->id); ?>" aria-expanded="false" aria-controls="semesters_<?php echo e($major->id); ?>"><?php echo e($major->major); ?></a></h4>
                    <ul id="semesters_<?php echo e($major->id); ?>" class="collapse">
                    <?php for($i = 1; $i <= 10; $i++): ?>
                        <?php if(count($major->courses()->where('semester','=',$i)->get())): ?>
                                <li><a class="select_semester" data-toggle="collapse" href="#courses_<?php echo e($major->id); ?>_<?php echo e($i); ?>" aria-expanded="false" aria-controls="courses_<?php echo e($major->id); ?>_<?php echo e($i); ?>">Semester <?php echo e($i); ?></a>

                                    <ul id="courses_<?php echo e($major->id); ?>_<?php echo e($i); ?>" class="collapse">
                                        <br><a class="select_all" href="#" major="<?php echo e($major->id); ?>" semester="<?php echo e($i); ?>">Select all</a>
                                        <?php foreach($major->courses()->where('semester','=',$i)->get() as $course): ?>
                                            <li>
                                                <input <?php echo e((in_array($course->id,$subscribed_courses))?'checked':''); ?> value="<?php echo e($course->id); ?>" type="checkbox" name="course[]" class="select_course course_<?php echo e($course->id); ?>">
                                                <?php echo e($course->course_name); ?>

                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>

                     <?php endfor; ?>
                    </ul>
                </li>
            <?php endforeach; ?>

            </ul>
            <input type="submit" class="btn btn-warning">
        </form>
    </div>


    <script>
        $('.select_all').click(function(){
            $(this).parent().find('input').attr('checked',true);
        });


        $('.select_course').change(function() {
            var course_id = $(this).val();
            $('.course_'+course_id).prop('checked',this.checked);
        });
    </script>

    <style>
        .select_major, .select_semester{
            color: #9A4838;
        }

        .select_major:hover, .select_semester:hover, .select_major:focus, .select_semester:focus{
            text-decoration: none;
        }
    </style>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>