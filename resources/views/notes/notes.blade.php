@extends('layouts.app')
@section('content')
<div id = "Header">

<h1 align="center"><em><Strong>Notes Page</Strong></em></h1>
</div>
<div id ="Note">
<ul>
  @foreach($notes as $note)
    <li>
      <h3><a href="/notes/view_note_details/{{$note->id}}">{{$note->title}}</a></h3>
      <p>{!! nl2br(e($note->description)) !!}</p>
      @if($verified_users_courses !== null)
          <a onclick="return confirm('Are you sure want to delete this note?');" href="/delete_note/{{$note->id}}">Delete</a>
      @elseif((!empty($role))&&$role==1)
        <a onclick="return confirm('Are you sure want to delete this note?');" href="/admin/delete_note/{{$note->id}}">Delete</a>
      @endif
      <!-- /browse/notes/view_note/{{$note->id}} -->
    </li>
  @endforeach
</ul>

<div style="text-align: center;">
   {{$notes->links()}}
</div>

</div>

@endsection
