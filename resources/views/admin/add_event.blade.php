@extends('layouts.app')
@section('content')

<style>
  h1{
    text-align: center;
    margin-bottom: 50px;
  }

  form{
    text-align: center;
    margin: auto;
  }

</style>


<h1 style="text-align:center">Add event</h1>
{{-- <div class="container"> --}}
  <form method="POST" action="{{url('admin/add_event')}}">

    <div class="form-group row">
      <label for="title" class="col-sm-2 col-form-label">Title</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="title" name="title" placeholder="title">
      </div>
    </div>



    <div class="form-group row">
      <label for="course" class="col-sm-2 col-form-label">Course</label>
      <div class="col-sm-5">
        <select class="form-control" id="course" name="course">
          @foreach ($courses as $course)
            <option value={{ $course['id'] }}>{{ $course['course_name']." ".$course['course_code'] }}</option>
          @endforeach
          </select>
      </div>
    </div>

    <div class="form-group row">
      <label for="date" class="col-sm-2 col-form-label">Date</label>
        <div class="col-sm-5">
          <input class="form-control" type="date"  id="date" name="date">
        </div>
    </div>

    <div class="form-group row">
      <label for="place" class="col-sm-2 col-form-label">Place</label>
      <div class="col-sm-5">
        <input type="text" class="form-control" id="place" name="place" placeholder="place">
      </div>
    </div>

    <div class="form-group row">
      <label for="description" class="col-sm-2 col-form-label">Description</label>
      <div class="col-sm-5">
      <textarea class="form-control" id="exampleTextarea" rows="3" name="description" placeholder="description"></textarea>
      </div>
    </div>

    <div class="form-group row">
      <div class="offset-sm-2 col-sm-10">
        <button type="submit" class="btn btn-primary">Add event</button>
      </div>
    </div>
  </form>
{{-- </div> --}}

@endsection
