<?php $__env->startSection('content'); ?>
<div class="container" style="width: 90%">
    <h1>Questions Reports</h1>
    <table class="table table-hover">
        <tr>
            <th>Reporter</th>
            <th>Question</th>
            <th>Reason</th>
        </tr>
        <?php foreach($question_reports as $report): ?>
            <tr href="<?php echo e($report->link); ?>" class="report_row">

                <td><?php echo e($report->reporter->first_name.' '.$report->reporter->last_name); ?></td>

                <td><?php echo e($report->question->question); ?></td>

                <td><?php echo e($report->report); ?></td>

            </tr>
        <?php endforeach; ?>
    </table>

    <h1>Answers Reports</h1>
    <table class="table-hover table">
        <tr>
            <th>Reporter</th>
            <th>Answer</th>
            <th>Reason</th>
        </tr>
        <?php foreach($answer_reports as $report): ?>
            <tr href="<?php echo e($report->link); ?>" class="report_row">
                <td><?php echo e($report->reporter->first_name.' '.$report->reporter->last_name); ?></td>
                <td><?php echo e($report->answer->answer); ?></td>
                <td><?php echo e($report->report); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

    <style>
        .report_row
        {
            cursor: pointer;
        }
    </style>
    <script>
        $('.report_row').click(function(){
            window.location.href = $(this).attr('href');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>