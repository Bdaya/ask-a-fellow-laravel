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
                @if(Auth::user())
                      <div class="delete_question pull-right">
                        @if(Auth::user()->id == $question->asker_id || Auth::user()->role >= 1)
                            <a onclick="return confirm('Are you sure want to delete this question?');" title="Delete Component Question" href="{{url('user/components/'.$component->id.'/delete/'.$question->id)}}"><span style="color:#FFAF6C" class="glyphicon glyphicon-remove"></span></a>
                        @endif
                      </div>
                  @endif
                  <p>{{ $question->question }}</p>
                  <h5>Asked By: {{ $question->asker()->first_name }}</h5>
                  <a href="{{ url('/user/view_component_answers/' . $question->id) }}" class="btn btn-xs btn-info pull-right">View Answers</a>
                  <br></br>
                @endforeach
              </div>
              <br>
              <br>
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

@endsection

