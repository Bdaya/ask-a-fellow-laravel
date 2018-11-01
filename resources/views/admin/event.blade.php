@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

              <div class="panel-heading">
              <h2>Event: {{ $event->title }}</h2>
              <br>
              <h4><i>Description: {{ $event->description }}</h4></i>
              <br>
              <h4><i>Course: {{ $event->course->course_name }} ({{ $event->course->course_code }})</h4></i>
              <br>
              <h4><i>Place: {{ $event->place }}</h4></i>
              <br>
              <h4><i>Date: {{ $event->date }}</h4></i>
              <br>
              <h2>Created by: {{ $creator->first_name }} {{ $creator->last_name }}</h2>
              <br>
              <h4><i>Email: {{ $creator->email }}</h4></i>
              <br>
              <h4><i>Major: {{ $creator->major }}</h4></i>
              <br>
              <h4><i>Semester: {{ $creator->semester }}</h4></i>
              </div>

              <div class="panel-body">
               
                <h3><a href="/admin/accept/{{ $event->id }}">Accept Request</a></h3>
				        <br>
                <h3><a onclick="return confirm('Are you sure want to reject this request?');" href="/admin/reject/{{ $event->id }}">Reject Request</a></h3>
               
              </div>
                
          </div>
          	
        </div>
    </div>
</div>

@endsection