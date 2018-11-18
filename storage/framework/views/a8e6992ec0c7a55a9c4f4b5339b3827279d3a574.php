

<table class="table table-hover">
    <tr id="head">
        <th>Course code</th>
        <th>Course name</th>
        <th>Questions</th>
        <th>Notes</th>
        <th>Events</th>
    </tr>
    <?php foreach($courses as $course): ?>
        <tr class="course_row" href="<?php echo e(url('browse/'.$course->id)); ?>">

                <td><?php echo e($course->course_code); ?></td>
                <td><?php echo e($course->course_name); ?></td>
                <td><?php echo e(count($course->questions()->get())); ?></td>
                <td>
                    <a href="<?php echo e(url('browse/notes/'.$course->id)); ?>">View Notes</a>
                    <br>
                    <a href="<?php echo e(url('/course/'.$course->id.'/uploadNote')); ?>">Upload Note</a>
                </td>
                <td>
                    <a href="events">View Events</a>
                    <br>
                    <?php if(Auth::user()->role >= 1): ?>
                        <a href="<?php echo e(url('/course/add_event/'.$course->id)); ?>">Add Event</a>
                    <?php endif; ?>
                </td>
        </tr>
    <?php endforeach; ?>
    <tr>
        <td>
            <a href="<?php echo e(url('/browse/questions/'.$major->id.'/'.$semester)); ?>">View questions from all courses</a>
        </td>
    </tr>
</table>

<style>
    .table{
        box-shadow: none;
        border: 1px solid #FFAF6C;
        border-collapse: separate;
    }
    .table td
    {
        border-top: 1px solid #FFAF6C;
        cursor: pointer;
    }
    .table th
    {
        border-bottom: 1px solid #FFAF6C;
    }
</style>
<script>
    $('.course_row').click(function(){
       window.location.href = $(this).attr('href');
    });
</script>
