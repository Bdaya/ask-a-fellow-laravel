<?php $__env->startSection('content'); ?>
    <style>
        table td, table th
        {
            border: 1px solid black;
            padding: 7px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/zf/dt-1.10.11/datatables.min.css"/>
    <div class="container">
        <table class="table table-striped table-bordered" style="width:100%;" id="courses_table">
            <thead>
            <tr>
                <th>Course code</th>
                <th>Course name</th>
                <th>Majors</th>`
                <th>Semester</th>
                <th>Delete</th>
                <th>Update</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($courses as $course): ?>
                <tr>
                    <td><?php echo e($course->course_code); ?></td>
                    <td><?php echo e($course->course_name); ?></td>
                    <td>
                        <ul>
                            <?php foreach($course->majors()->get() as $major): ?>
                                <li><?php echo e($major->major); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </td>
                    <td><?php echo e($course->semester); ?></td>
                    <td><a onclick="return confirm('Are you sure?');" href="<?php echo e(url('admin/delete_course/'.$course->id)); ?>">Delete</a></td>
                    <td><a href="<?php echo e(url('admin/update_course/'.$course->id)); ?>">Update</a></td>
                </tr>


            <?php endforeach; ?>
            </tbody>
        </table>
        <form method="POST" action="<?php echo e(url('admin/add_course')); ?>" style="padding: 50px; width: 50%;">
            <?php echo e(csrf_field()); ?>

            <div class="form-group">
                <label for="course_code">Course Code</label>
                <input type="text" class="form-control" id="course_code" name="course_code" placeholder="Course Code" value="<?php echo e(old('course_code')); ?>">
            </div>
            <div class="form-group">
                <label for="course_name">Course Name</label>
                <input type="text" class="form-control" id="course_name" name="course_name" placeholder="Course Name" value="<?php echo e(old('course_name')); ?>">
            </div>
            <div class="form-group">
                <label for="semester">Semester</label>
                <input type="number" min="1" max="10" class="form-control" id="semester" name="semester" placeholder="Semester" value="<?php echo e(old('semester')); ?>">
            </div>
            <div class="form-group">
                <label for="majors">Majors</label>
                <br>
                <?php foreach($majors as $major): ?>
                    <input type="checkbox" name="majors[]" value="<?php echo e($major->id); ?>"> <?php echo e($major->major); ?> <br>
                <?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-default">Add Course</button>
            <br></br>
            <div class="error" style="color:red">
                <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </div>
            <?php if(Session::has('Added')): ?>
                <div class="alert alert-info"><?php echo e(Session::get('Added')); ?></div>
            <?php endif; ?>
            <?php if(Session::has('Updated')): ?>
                <div class="alert alert-info"><?php echo e(Session::get('Updated')); ?></div>
            <?php endif; ?>
        </form>
    </div>
    <script type="text/javascript" src="https://cdn.datatables.net/t/zf/dt-1.10.11/datatables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#courses_table').DataTable();
        } );
    </script>

    <style>
        #courses_table_wrapper
        {
            width: 70%;
        }
        .odd {
            background-color: #FFECDC !important;
        }

        #courses_table thead tr {
            background-color: #FFCEA5;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>