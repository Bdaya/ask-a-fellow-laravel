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

@endsection

