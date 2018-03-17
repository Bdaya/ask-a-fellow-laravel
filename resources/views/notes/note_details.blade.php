 @extends('layouts.app');
@section('content')

    <div class="container">
        <div class="media question">
            <div style="text-align: center" class="media-left">

                <a href="{{url('user/'.$note->user_id)}}">
                  @if($note->user->profile_picture)
                    <img class="media-object" src="{{asset($note->user->profile_picture)}}" alt="Profile Photo not Found!" width="75" height="75">
                  @else
                    <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="..." width="75" height="75">
                  @endif
                </a>

                <br>
                @if(Auth::user() && (Auth::user()->role >= 1))
                  <form action='/admin/delete_note/{{$note->id}}' Method="GET">
                    <button class ="icon-button"><span class="glyphicon glyphicon-trash" style="font-size:30px"></span></button>
                  </form>
                @endif

            </div>

            <div class="media-body">
               @if($note->user->verified_badge >=1)
                  <h3>Uploaded by <a href="{{url('user/'.$note->user_id)}}">{{$note->user->first_name.' '.$note->user->last_name}} <span class="verified"></span></a></h3>
                @else
                  <h3>Uploaded by <a href="{{url('user/'.$note->user_id)}}">{{$note->user->first_name.' '.$note->user->last_name}} </a></h3>
                @endif

                <h2>{{$note->title}}</h2>
                <div class="note_description">
                    <h4>{{$note->description}}</h4>
                </div>

                <p style="font-weight: bold; font-style: italic; ">{{ date("F j, Y, g:i a",strtotime($note->created_at)) }} </p>
            </div>
        </div>

        <div>
          @if(Auth::user())
              <a class="upvote_note vote" value="{{$note->id}}" title="upvote" style="color:green; font-size:50px"><span class="glyphicon glyphicon-thumbs-up"></span></a>
          @endif
          @if($note->votes > 0)
              <span class="note_votes" style="color:green;">{{$note->votes}} </span>
          @elseif($note->votes == 0)
              <span class="note_votes" style="">{{$note->votes}} </span>
          @else
              <span class="note_votes" style="color:red;">{{$note->votes}} </span>
          @endif
          &nbsp;&nbsp;&nbsp;&nbsp;
          @if(Auth::user())
              <a class="downvote_note vote" value="{{$note->id}}"  title="downvote"  style="color:red; font-size:50px"><span class="glyphicon glyphicon-thumbs-down"></span></a>
          @endif

          <h4><a href="{{url('browse/notes/view_note/'.$note->id)}}">Click to download</a></h4>

        </div>

        <h2 style="">{{count($note->comments()->get())}} Comment(s)</h2>
 
        <div class="comments">

            @foreach($comments as $comment)
                <div class="media answer">
                    <div style="text-align: center" class="media-left">

                        <a href="{{url('user/'.$comment->user_id)}}">

                            @if($comment->commenter->profile_picture)
                                <img class="media-object" src="{{asset($comment->commenter->profile_picture)}}" alt="..."  width="75" height="75">
                            @else
                                <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="..."  width="75" height="75">
                            @endif
                        </a>
                    </div>
                    <div class="media-body">
                            @if($comment->commenter->verified_badge >=1)
                                <h3>{{$comment->commenter->first_name.' '.$comment->commenter->last_name}}<span class="verified"></span></h3>
                             @else
                                <h3>{{$comment->commenter->first_name.' '.$comment->commenter->last_name}}</h3>
                            @endif

                        <div class="comment_body">
                            {{$comment->body}}
                        </div>
                        <p style="font-weight: bold; font-style: italic; ">{{ date("F j, Y, g:i a",strtotime($comment->created_at)) }} </p>
                    </div>
                </div>
            @endforeach
        </div>

        @if(Auth::user())
        <form id="post_comment_form" action="/note_comment/{{$note->id}}" method="POST">
            {{csrf_field()}}
            <div class="form-group">
                <label for="post_comment_body">Post a comment:</label>
                <textarea required class="form-control" id="post_comment_body" name="comment" placeholder="Type your comment here"></textarea>
                <input type="submit" value="Post Answer" class="btn btn-default pull-right" id="post_comment_submit">
            </div>
        </form>
        @else
            <div class="">You must be logged in to be able to answer. <a href="{{url('/login')}}">Login here.</a></div>
        @endif
    </div>



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
    background-image: url("{{asset('art/ver.png')}}");
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
        $('#report_other_text').hide();
    });



    $('.upvote_note').click(function () {
        var note_id = $(this).attr('value');
        var type = 0;
        var note = $(this);
        $.ajax({
            'url': "{{url('')}}/vote/note/" + note_id + "/" + type,
            success: function (data) {
                note.parent().find('.note_votes').html(data);
            }
        });
    });

    $('.downvote_note').click(function () {
        var note_id = $(this).attr('value');
        var type = 1;
        var note = $(this);
        $.ajax({
            'url': "{{url('')}}/vote/note/" + note_id + "/" + type,
            success: function (data) {
                console.log(data);
                note.parent().find('.note_votes').html(data);
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
</script>



@endsection
