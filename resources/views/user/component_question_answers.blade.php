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
                        <p>{{ $answer->answer }}</p>
                        <h5>Answered By: {{ $answer->responder()->first_name }}</h5>
                    @endforeach
              </div>
              <br>
              <br>
              <div>
                  <form id="component_answer_form" action="{{ url('user/post_component_answer/'.$question->id) }}" method="POST">
                      {{--  {{csrf_field()}}  --}}
                      <div class="form-group">
                          <label for="component_answer_text">Post an answer:</label>
                          <textarea required class="form-control" id="component_answer_text" name="answer"
                                    placeholder="Type your answer here"></textarea>
                          <input type="submit" value="Post Answer" class="btn btn-default pull-right"
                                 id="component_answer_submit">
                      </div>
                  </form>
              </div>

            </div>
        </div>
    </div>
</div>

@endsection

