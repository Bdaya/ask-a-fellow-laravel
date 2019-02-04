 ;
<?php $__env->startSection('content'); ?>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" integrity="sha384-hWVjflwFxL6sNzntih27bfxkr27PmbbK/iSvJ+a4+0owXq79v+lsFkW54bOGbiDQ" crossorigin="anonymous">

    <div class="container">

         <?php if(Session::has('success')): ?>
            <div class="alert alert-info" style="width: 87.5%"><?php echo e(Session::get('success')); ?></div>
        <?php endif; ?>
        <div class="media question">

            <div style="text-align: center" class="media-left">

                <a href="<?php echo e(url('user/'.$note->user_id)); ?>">
                  <?php if($note->user->profile_picture): ?>
                    <img class="media-object" src="<?php echo e(asset($note->user->profile_picture)); ?>" alt="Profile Photo not Found!" width="75" height="75">
                  <?php else: ?>
                    <img class="media-object" src="<?php echo e(asset('art/default_pp.png')); ?>" alt="..." width="75" height="75">
                  <?php endif; ?>
                </a>

                <br>
                <?php if(Auth::user()): ?>
                    <?php if(Auth::user()->role >= 1 ): ?>
                      <form onclick="return confirm('Are you sure want to delete this note?');" action='/admin/delete_note/<?php echo e($note->id); ?>' Method="GET">
                        <button class ="icon-button"><span class="glyphicon glyphicon-trash" style="font-size:30px"></span></button>
                      </form>
                    <?php elseif(Auth::user()->id == $note->user_id || $verified_users_courses !== null): ?>
                        <form onclick="return confirm('Are you sure want to delete this note?');" action='/delete_note/<?php echo e($note->id); ?>' Method="GET">
                            <button class ="icon-button"><span class="glyphicon glyphicon-trash" style="font-size:30px"></span></button>
                        </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="media-body">
               <?php if($note->user->verified_badge >=1): ?>
                  <h3>Uploaded by <a href="<?php echo e(url('user/'.$note->user_id)); ?>"><?php echo e($note->user->first_name.' '.$note->user->last_name); ?> <span class="verified"></span></a></h3>
                <?php else: ?>
                  <h3>Uploaded by <a href="<?php echo e(url('user/'.$note->user_id)); ?>"><?php echo e($note->user->first_name.' '.$note->user->last_name); ?> </a></h3>
                <?php endif; ?>

                <h2><?php echo e($note->title); ?></h2>
                <div class="note_description">
                    <h4><?php echo e($note->description); ?></h4>
                </div>

                <p style="font-weight: bold; font-style: italic; "><?php echo e(date("F j, Y, g:i a",strtotime($note->created_at))); ?> </p>
            </div>
        </div>

        <div id="delete_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class=""  style="background-color:rgba(255,255,255,0.9)">

                        <button style="margin-right:15px;margin:top:10px;"type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


                        <br>
                        <div class="modal-body" style="padding: 0 50px 40px 50px;">
                            <h3>Write your delete comment</h3>
                            <div class="form-group" style="width: 100%;">
                                <textarea class="form-control delete_note_comment"></textarea>
                            </div>

                            <button onclick="deleteNoteRequest()" class="btn btn-default">Submit</button>
                            <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                        </div>
                    </div>
                </div>
            </div>

        <div>
        <?php if(Auth::user() && count(Auth::user()->upvotesOnNote($note->id))): ?>
            <i class="fa fa-thumbs-up upvote_note" value="<?php echo e($note->id); ?>" title="upvote" style="color:green; font-size: 28px"></i>
        <?php elseif(Auth::user()): ?>
            <i class="far fa-thumbs-up upvote_note" value="<?php echo e($note->id); ?>" title="upvote" style="color:green; font-size: 28px"></i>
        <?php endif; ?>
          &nbsp;&nbsp;&nbsp;&nbsp;
          <?php if($note->votes > 0): ?>
              <span class="note_votes" style="color:green; font-size:30px"><?php echo e($note->votes); ?> </span>
          <?php elseif($note->votes == 0): ?>
              <span class="note_votes" style="font-size:30px"><?php echo e($note->votes); ?> </span>
          <?php else: ?>
              <span class="note_votes" style="color:red; font-size:30px"><?php echo e($note->votes); ?> </span>
          <?php endif; ?>
          &nbsp;&nbsp;&nbsp;&nbsp;
        <?php if(Auth::user() && count(Auth::user()->downvotesOnNote($note->id))): ?>
            <i class="fa fa-thumbs-down downvote_note" value="<?php echo e($note->id); ?>" title="downvote" style="color:red; font-size: 28px"></i>
        <?php elseif(Auth::user()): ?>
            <i class="far fa-thumbs-down downvote_note" value="<?php echo e($note->id); ?>" title="downvote" style="color:red; font-size: 28px"></i>
        <?php endif; ?>

          <h4><a href="<?php echo e(url('browse/notes/view_note/'.$note->id)); ?>"><span class="glyphicon glyphicon-paperclip"></span> <?php echo e($filename); ?></a></h4>

        </div>

        <h2 style=""><?php echo e(count($note->comments()->get())); ?> Comment(s)</h2>
 
        <div class="comments">

            <?php foreach($comments as $comment): ?>
                <div class="media answer">
                    <div style="text-align: center" class="media-left">

                        <?php if(Auth::user()): ?>
                          <div>
                            <?php if(Auth::user()->id == $comment->user_id): ?>
                                <a value="<?php echo e($comment); ?>" data-toggle="modal" data-target="#edit_modal" class="edit_comment" title="Edit Note Comment"><span class="glyphicon glyphicon-edit" style="color:#D24848;cursor:pointer;"></span></a>
                            <?php endif; ?>
                            <?php if(Auth::user()->id == $comment->user_id || Auth::user()->role >= 1 || $verified_users_courses !== null): ?>
                                <a onclick="return confirm('Are you sure want to delete this comment?');" title="Delete Note Comment" href="<?php echo e(url('delete_note_comment/'.$note->id.'/'.$comment->id)); ?>"><span style="color:#FFAF6C" class="glyphicon glyphicon-remove"></span></a>
                                <br>
                            <?php endif; ?>
                          </div>
                      <?php endif; ?>

                        <a href="<?php echo e(url('user/'.$comment->user_id)); ?>">

                            <?php if($comment->commenter->profile_picture): ?>
                                <img class="media-object" src="<?php echo e(asset($comment->commenter->profile_picture)); ?>" alt="..."  width="75" height="75">
                            <?php else: ?>
                                <img class="media-object" src="<?php echo e(asset('art/default_pp.png')); ?>" alt="..."  width="75" height="75">
                            <?php endif; ?>
                        </a>
                    </div>
                    <div class="media-body">
                            <?php if($comment->commenter->verified_badge >=1): ?>
                                <h3><?php echo e($comment->commenter->first_name.' '.$comment->commenter->last_name); ?><span class="verified"></span></h3>
                             <?php else: ?>
                                <h3><?php echo e($comment->commenter->first_name.' '.$comment->commenter->last_name); ?></h3>
                            <?php endif; ?>

                        <div class="comment_body">
                            <?php echo e($comment->body); ?>

                        </div>
                        <p style="font-weight: bold; font-style: italic; "><?php echo e(date("F j, Y, g:i a",strtotime($comment->created_at))); ?> </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="text-align: center;">
           <?php echo e($comments->links()); ?>

        </div>

         <div id="edit_modal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class=""  style="background-color:rgba(255,255,255,0.9)">

                    <button style="margin-right:15px;margin:top:10px;"type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


                    <br>
                    <div class="modal-body" style="padding: 0 50px 40px 50px;">
                        <h3>Edit Comment</h3>
                        <div class="form-group" style="width: 100%;">
                            <textarea class="form-control modified_comment"></textarea>
                        </div>

                        <button disabled="disabled" onclick="editComment()" class="btn btn-default">Edit</button>
                        <?php echo $__env->make('errors', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                    <!-- <div class="modal-footer"> -->

                    <!-- </div> -->
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <?php if(Auth::user()): ?>
        <form id="post_comment_form" action="/note_comment/<?php echo e($note->id); ?>" method="POST">
            <?php echo e(csrf_field()); ?>

            <div class="form-group">
                <label for="post_comment_body">Post a comment:</label>
                <textarea required class="form-control" id="post_comment_body" name="comment" placeholder="Type your comment here"></textarea>
                <input type="submit" value="Post Answer" class="btn btn-default pull-right" id="post_comment_submit">
            </div>
        </form>
        <?php else: ?>
            <div class="">You must be logged in to be able to comment. <a href="<?php echo e(url('/login')); ?>">Login here.</a></div>
        <?php endif; ?>

    </div>
    <br>



<style>
.icon-button {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    outline: none;
    border: 0;
    background: transparent;
}

#Header h1{
  position:absolute;
  left:40%;
  top:10%;
  margin:auto;

   text-decoration: underline;
}

.note
{
    width: 80%;
    /*min-width: 200px;*/
    margin-bottom: 50px;
}
.note img
{
    width: 70px;
    height: 70px;
    border-radius: 100px;
    margin-bottom: 10px;
}
.note h3
{
    /*width: 100%;*/
    font-size: 20px;
    margin-top: 2px;
    color: #621708;
    /*font-weight: bold;*/
}

.note .note_description
{
    font-size: 22px;
}

.comment
{
    background-color:  #FFF5E9;
    padding: 15px;
    margin-left: 80px;
    width: 60%;
    /*min-width: 200px;*/
    margin-bottom: 10px;
}
.comment img
{
    width: 50px;
    height: 50px;
    border-radius: 100px;
    margin-bottom: 10px;
}
.comment h3
{
    /*width: 100%;*/
    font-size: 18px;
    margin-top: 2px;
    color: #621708;
    /*font-weight: bold;*/
}

.comment .comment_body
{
    font-size: 15px;
}


.vote
{
    cursor: pointer;
}

#post_comment_form
{
    width: 60%;
    margin-left: 90px;
    margin-top: 50px;
}
#post_comment_form textarea
{
    resize: none;
    height:150px;
    font-size: 18px;
}
#post_comment_form #post_comment_submit
{
    background-color: #FFE9CF;
    border: 1px solid #CCB69C;
    margin-top: 10px;
}
#post_comment_form #post_comment_submit:focus
{
    background-color: #CCB69C;
    /*border: 1px solid #CCB69C !important;*/

}


@media (max-width:800px)
{
    .note,.comment,#post_comment_form
    {
        margin-left:-30px;
        min-width: 280px;
        width: 90%;

    }

    h1,h2
    {
        font-size: 23px;
    }


    .note_description
    {
        font-size:10px;
    }


}
span.verified{
    display: inline-block;
    vertical-align: middle;
    height: 40px;
    width: 40px;
    background-image: url("<?php echo e(asset('art/ver.png')); ?>");
    background-repeat: no-repeat;
    z-index: 200000;

}

</style>

<script>
    $(document).ready(function(){
        if($('#post_comment_body').val().trim().length == 0)
            $('#post_comment').attr('disabled',true);
        else
            $('#post_comment_body').attr('disabled',false);
    });



    $('.upvote_note').click(function () {
        var note_id = $(this).attr('value');
        var type = 0;
        var note = $(this);
        $.ajax({
            'url': "<?php echo e(url('')); ?>/vote/note/" + note_id + "/" + type,
            success: function (data) {
                location.reload();
            }
        });
    });

    $('.downvote_note').click(function () {
        var note_id = $(this).attr('value');
        var type = 1;
        var note = $(this);
        $.ajax({
            'url': "<?php echo e(url('')); ?>/vote/note/" + note_id + "/" + type,
            success: function (data) {
                location.reload();
            }
        });
    });

    $('#post_comment_body').keyup(function()
    {
        if($(this).val().trim().length == 0)
            $('#post_comment_submit').attr('disabled',true);
        else
            $('#post_comment_submit').attr('disabled',false);

    });

    var comment_id;
        $('.edit_comment').click(function () {
            var comment = $(this).attr('value');
            comment_id = JSON.parse(comment)["id"];
            var body = JSON.parse(comment)["body"];
            $('.modified_comment').val(body);
        });

    $(function() {
        $('.modified_comment').on('input', function() {
            if( $('.modified_comment').filter(function() { return !!this.value; }).length > 0 ) {
                 $('button').prop('disabled', false);
            } else {
                 $('button').prop('disabled', true);
            }
        });
    });
    
    function editComment(){
            var body = $('.modified_comment').val();
            $.ajax({
                type: "GET",
                url : "<?php echo e(url('edit_comment/')); ?>",
                data : {comment:body,comment_id:comment_id},
                success : function(data){
                    location.reload();
                }
            });
    }

    var note_id;
        $('.deletion_comment').click(function () {
            note_id = $(this).attr('value');
        });
    
    function deleteNoteRequest(){
            var body = $('.delete_note_comment').val();
            $.ajax({
                type: "GET",
                url : "<?php echo e(url('note/request_delete/')); ?>",
                data : {comment:body,note_id:note_id},
                success: function (data) {
                    location.reload();
                }
            });
    }
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>