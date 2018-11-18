<?php $__env->startSection('content'); ?>
    <div class="container" style="width:90%; padding-left: 50px;">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/zf/dt-1.10.11/datatables.min.css"/>
        <table id="mails_table" class="table table-bordered">
            <thead>
                <tr>
                    <th>
                        Sender
                    </th>
                    <th>
                        Mail subject
                    </th>
                    <th>
                        Mail Body
                    </th>
                    <th>
                        Recipients
                    </th>
                    <th>
                        Date
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($mails as $mail): ?>
                    <tr>
                        <td><a href="<?php echo e(url('/user/'.$mail->sender->id)); ?>"><?php echo e($mail->sender->first_name.' '.$mail->sender->last_name); ?></a></td>
                        <td><?php echo e($mail->subject); ?></td>
                        <td><?php echo $mail->body; ?></td>
                        <td>
                            <ul>
                                <?php foreach($mail->recipients as $rec): ?>
                                    <li><a href="<?php echo e(url('user/'.$rec->id)); ?>"><?php echo e($rec->first_name.' '.$rec->last_name); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td><?php echo e($mail->created_at); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script type="text/javascript" src="https://cdn.datatables.net/t/zf/dt-1.10.11/datatables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#mails_table').DataTable();
        } );
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>