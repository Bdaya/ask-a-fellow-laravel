@extends('layouts.app');

@section('content')
  <h1 style="text-align:center"> Notes Upload Requests </h1>
   <div class="container" >
   @if(!$notes_upload)
    <h3 style="text-align: center; margin-top: 50px; margin-bottom: 50px"> No note upload requests. </h3>
    @endif
    @foreach($notes_upload as $note)
        <div class="row">
          <div class="col-md-6">
           <h3> {{$note->title}} </h3>  <br>
          <b>Description: </b>  {{$note->description}} <br>
          <b>Uploaded by: </b>  {{$note->first_name}}  {{$note->last_name}} <br>
          <b>Course: </b>{{$note->course_name}} {{$note->course_code}} <br>



          </div>

          <div style="margin-top: 50px" class="col-md-3 button-group">
            <a href="/admin/approve_note/{{$note->id}}" class="upload btn btn-success" id="{{$note->id}}">Upload</a>
            <a href="/admin/delete_note/{{$note->id}}" class="delete btn btn-danger" id="{{$note->id}}">Delete</a>
            <a href="/admin/view_note/{{$note->id}}" class="delete btn btn-primary" id="{{$note->id}}">View</a>
          </div>
        </div>

    @endforeach
   </div>
   <hr>
  <h1 style="text-align:center"> Notes Delete Requests </h1>
  @if(!$notes_delete)
    <h3 style="text-align: center; margin-top: 50px; margin-bottom: 50px"> No note delete requests. </h3>
    @endif
   <div class="container" >
    @foreach($notes_delete as $note)
        <div class="row">
          <div class="col-md-6">
           <h3> {{$note->title}} </h3>  <br>
          <b>Description: </b>  {{$note->description}} <br>
          <b>Uploaded by: </b>  {{$note->first_name}}  {{$note->last_name}} <br>
          <b>Course: </b>{{$note->course_name}} {{$note->course_code}} <br>
          <b>Delete comment:</b>{{$note->comment_on_delete}} <br>


          </div>

          <div style="margin-top: 50px" class="col-md-3 button-group">
            <a href="/admin/delete_note/{{$note->id}}" class="delete btn btn-danger" id="{{$note->id}}">Delete</a>
            <a href="/admin/view_note/{{$note->id}}" class="delete btn btn-primary" id="{{$note->id}}" target="_blank">View</a>
          </div>
        </div>

    @endforeach
   </div>



@endsection
