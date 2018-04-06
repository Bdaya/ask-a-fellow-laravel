@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

              <div class="panel-heading">
                <h1>Component Details</h1>
              </div>

              <div class="panel-body">
                <img src="{{asset($component->image_path)}}" alt="No Image Found!" width="100" height="100">
                <h2 align="center">{{ $component->title }}</h2>
                <br>
                <h3>Price: {{ $component->price }} EGP</h3>
                <h3>Description: {{ $component->description }}</h3>
                <h3>Category: {{ $component->category()->name }}</h3>
                <h3>Posted by: {{ $component->creator()->first_name }} {{ $component->creator()->last_name }}</h3>
                <h3>Contact Info:</h3>
                <p>Email: {{ $component->creator()->email }}</p>
                <p>Other: {{ $component->contact_info }}</p>
              </div>

              <div class="panel-heading">
                <h1>Questions</h1>
              </div>
              
              <div class="panel-body">
                @foreach($questions as $question)
                <div class="media answer">
                    <div style="text-align: center" class="media-left">

                      @if(Auth::user())
                          <div>
                            @if(Auth::user()->id == $question->asker_id)
                                <a value="{{$question}}" data-toggle="modal" data-target="#edit_modal" class="edit_question" title="Edit Component Question"><span class="glyphicon glyphicon-edit" style="color:#D24848;cursor:pointer;"></span></a>
                            @endif
                            @if(Auth::user()->id == $question->asker_id || Auth::user()->role >= 1)
                                <a onclick="return confirm('Are you sure want to delete this question?');" title="Delete Component Question" href="{{url('user/components/'.$component->id.'/delete/'.$question->id)}}"><span style="color:#FFAF6C" class="glyphicon glyphicon-remove"></span></a>
                                <br>
                            @endif
                          </div>
                      @endif

                        <a href="{{url('user/'.$question->asker_id)}}">

                            @if($question->asker()->profile_picture)
                                <img class="media-object" src="{{asset($question->asker()->profile_picture)}}" alt="..."  width="50" height="50">
                            @else
                                <img class="media-object" src="{{asset('art/default_pp.png')}}" alt="..."  width="50" height="50">
                            @endif
                        </a>
                    </div>
                    <div class="media-body">
                        <h5>Asked By: {{ $question->asker()->first_name }}</h5>

                        <div>
                          <p>{{ $question->question }}</p>
                          @if($question->attachement_path)
                            <div>
                              <span class="glyphicon glyphicon-paperclip"></span>
                              <a href="{{url('/user/component_question/download_attachement/'.$question->id)}}">{{ $question->attachement_path }}</a>
                            </div>
                          @endif
                        </div>
                        <p style="font-weight: bold; font-style: italic; ">{{ date("F j, Y, g:i a",strtotime($question->created_at)) }} </p>
                        <a href="{{ url('/user/view_component_answers/' . $question->id) }}" class="btn btn-xs btn-info pull-right">View Answers</a>
                        <br></br>
                    </div>
                  </div>
                @endforeach
              </div>
              
              <div id="edit_modal" class="modal fade" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class=""  style="background-color:rgba(255,255,255,0.9)">

                        <button style="margin-right:15px;margin:top:10px;"type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>


                        <br>
                        <div class="modal-body" style="padding: 0 50px 40px 50px;">
                            <h3>Edit Component Question</h3>
                            <div class="form-group" style="width: 100%;">
                                <textarea class="form-control modified_question"></textarea>
                            </div>

                            <button disabled="disabled" onclick="editQuestion()" class="btn btn-default">Edit</button>
                            @include('errors')
                        </div>
                        <!-- <div class="modal-footer"> -->

                        <!-- </div> -->
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
              <br></br>
              <div class="panel-body">
                  <form id="component_question_form" action="{{ url('user/post_component_question/'.$component->id) }}" enctype="multipart/form-data" method="POST">
                      {{csrf_field()}}
                      <div class="form-group">
                          <label for="component_question_text">Ask a question:</label>
                          <textarea required class="form-control" id="component_question_text" name="question"
                                    placeholder="Type your question here"></textarea>
                          <br>
                          <div class="form-group">
                            <div class="col-sm-5 pull-right">
                                <input name="filepath" id="filepath" type="file">
                            </div>
                            <label for="filepath" class="col-sm-1.5 control-label pull-right">Attach file</label>
                          </div>
                          <br>
                          <div class="form-group">
                              <div class="col-sm-offset-3">
                                  <button type="submit" class="btn btn-default pull-right">Post Question</button>
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

  var question_id;
  $('.edit_question').click(function () {
      var question = $(this).attr('value');
      question_id = JSON.parse(question)["id"];
      var body = JSON.parse(question)["question"];
      $('.modified_question').val(body);
  });

  $(function() {
    $('.modified_question').on('input', function() {
        if( $('.modified_question').filter(function() { return !!this.value; }).length > 0 ) {
             $('button').prop('disabled', false);
        } else {
             $('button').prop('disabled', true);
        }
    });
  });

  function editQuestion(){
    var body = $('.modified_question').val();
    $.ajax({
        type: "GET",
        url : "{{url('edit_component_question/')}}",
        data : {question:body,question_id:question_id},
        success : function(data){
            location.reload();
        }
    });
  }
</script>

@endsection

