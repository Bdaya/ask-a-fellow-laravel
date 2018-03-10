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
              
              <div>
                @foreach($questions as $question)
                  <p>{{ $question->question }}</p>
                  <h5>Asked By: {{ $question->asker()->first_name }}</h5>
                  <a href="{{ url('/user/view_component_answers/' . $question->id) }}" class="btn btn-xs btn-info pull-right">View Answers</a>
                  <br></br>
                @endforeach
              </div>
              <br>
              <br>
              <div>
                  <form id="component_question_form" action="{{ url('user/post_component_question/'.$component->id) }}" method="POST">
                      {{--  {{csrf_field()}}  --}}
                      <div class="form-group">
                          <label for="component_question_text">Ask a question:</label>
                          <textarea required class="form-control" id="component_question_text" name="question"
                                    placeholder="Type your question here"></textarea>
                          <input type="submit" value="Post Question" class="btn btn-default pull-right"
                                 id="component_question_submit">
                      </div>
                  </form>
              </div>

            </div>
        </div>
    </div>
</div>

@endsection

