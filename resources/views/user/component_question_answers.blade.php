@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

              <div class="panel-heading">
                <h3>{{ $question->question }}</h3>
              </div>

              <div class="panel-body">
                    @foreach($answers as $answer)
                      @if(Auth::user())
                        <div class="delete_answer pull-right">
                          @if(Auth::user()->id == $question->asker_id)
                              <a value="{{$answer}}" data-toggle="modal" data-target="#edit_modal" class="edit_answer" title="Edit Component Answer"><span class="glyphicon glyphicon-edit" style="color:#D24848;cursor:pointer;"></span></a>
                          @endif
                          @if(Auth::user()->id == $answer->responder_id || Auth::user()->role >= 1)
                              <a onclick="return confirm('Are you sure want to delete this answer?');" title="Delete Component Answer" href="{{url('user/delete_component_answers/'.$question->id.'/'.$answer->id)}}"><span style="color:#FFAF6C" class="glyphicon glyphicon-remove"></span></a>
                          @endif
                        </div>
                      @endif
                      <p>{{ $answer->answer }}</p>
                      @if($answer->attachement_path)
                        <div>
                          <span class="glyphicon glyphicon-paperclip"></span>
                          <a href="{{url('/user/component_answer/download_attachement/'.$answer->id)}}">{{ $answer->attachement_path }}</a>
                        </div>
                      @endif
                      <h5>Answered By: {{ $answer->responder()->first_name }}</h5>
                    @endforeach
              </div>

              <div style="text-align: center;">
                 {{$answers->links()}}
              </div>

              <div id="edit_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class=""  style="background-color:rgba(255,255,255,0.9)">

                        <button style="margin-right:15px;margin:top:10px;"type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


                        <br>
                        <div class="modal-body" style="padding: 0 50px 40px 50px;">
                            <h3>Edit Answer</h3>
                            <div class="form-group" style="width: 100%;">
                                <textarea class="form-control modified_answer"></textarea>
                            </div>

                            <button disabled="disabled" onclick="editAnswer()" class="btn btn-default">Edit</button>
                            @include('errors')
                        </div>
                        <!-- <div class="modal-footer"> -->

                        <!-- </div> -->
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>

              <br>
              <br>
              <div class="panel-body">
                  <form id="component_answer_form" action="{{ url('user/post_component_answer/'.$question->id) }}" enctype="multipart/form-data" method="POST">
                      {{ csrf_field() }}
                      <div class="form-group">
                          <label for="component_answer_text">Post an answer:</label>
                          <textarea required class="form-control" id="component_answer_text" name="answer"
                                    placeholder="Type your answer here"></textarea>
                          <br>
                          <div class="form-group">
                            <div class="col-sm-5 pull-right">
                                <input name="file" id="file" type="file">
                            </div>
                            <label for="file" class="col-sm-1.5 control-label pull-right">Attach file</label>
                          </div>
                          <br>
                          <div class="form-group">
                              <div class="col-sm-offset-3">
                                  <button type="submit" class="btn btn-default pull-right">Post Answer</button>
                              </div>
                          </div>
                      </div>
                  </form>
              </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  var answer_id;
      $('.edit_answer').click(function () {
          var answer = $(this).attr('value');
          answer_id = JSON.parse(answer)["id"];
          var body = JSON.parse(answer)["answer"];
          $('.modified_answer').val(body);
      });

      $(function() {
        $('.modified_answer').on('input', function() {
            if( $('.modified_answer').filter(function() { return !!this.value; }).length > 0 ) {
                 $('button').prop('disabled', false);
            } else {
                 $('button').prop('disabled', true);
            }
        });
      });

      function editAnswer(){
        var body = $('.modified_answer').val();
        $.ajax({
            type: "GET",
            url : "{{url('edit_answer/')}}",
            data : {answer:body,answer_id:answer_id},
            success : function(data){
                location.reload();
            }
        });
    }
</script>

@endsection

