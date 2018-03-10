@extends('layouts.app')
@section('content')
<div id = "Header">

<h1 align="center"><em><Strong>NOTES WALL</Strong></em></h1>
</div>
<div id ="Note">
<ul>
  @foreach($notes as $note)
    <li>
      <h3><a href="/notes/view_note_details/{{$note->id}}">{{$note->title}}</a></h3>
      <p>{!! nl2br(e($note->description)) !!}</p>
      @if((!empty($role))&&$role==1)
          <a href="/admin/delete_note/{{$note->id}}">Delete</a>
      @endif
      <!-- /browse/notes/view_note/{{$note->id}} -->
    </li>
  @endforeach
</ul>
</div>

@endsection
