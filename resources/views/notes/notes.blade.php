@extends('layouts.app')
@section('content')
<div id = "Header">

<h1 > <em><Strong>NOTES WALL</Strong></em></h1>
</div>
<div id ="Note">
<ul>
  	@foreach($notes as $note)
  <li>

    
      @if((!empty($role))&&$role==1)
        <form action='/admin/delete_note/{{$note->id}}' Method="GET">

        
        </form>
      @endif
      <h2  ><a  href="/notes/view_note_details/{{$note->id}}"> {{$note->title}} </a></h2>
      <hr />
      <p>{!! nl2br(e($note->description)) !!}</p>
      <hr />
      <form action='/browse/notes/view_note/{{$note->id}}' Method="GET">

        <button style="float:right" class ="icon-button2">View Note</button>
      </form>
    </a>
  </li>
  	@endforeach
</ul>
</div>

<style>
.icon-button {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    outline: none;
    border: 0;
    background: transparent;
}
.icon-button2{
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    outline: none;
    border: 0;
    background: transparent;
}
.icon-button2:hover{
  background-color: #40a0ff; /* Green */
color: white;
}
#Header h1{
  position:absolute;
  left:40%;
  top:10%;
  margin:auto;

   text-decoration: underline;
}
#Note * {
    margin: 0;
    padding: 0;
}

#Note body {
    font-family: arial, sans-serif;
    font-size: 100%;
    margin: 3em;
    background: #666;
    color: #fff;
}

#Note h2, p {
    font-size: 100%;
    font-weight: normal;
    margin-left:-20px;
}
#Note a p{
  margin-left:-20px;
}

#Note ul, li {
    list-style: none;
}

#Note ul {
    overflow: hidden;
    padding: 3em;
}

#NoteA {
    text-decoration: none;
    color: #000;
    background: #ffc;
    display: block;
    height: 20em;
    width: 20em;
    padding: 2em;
    word-wrap:break-word;
}

#Note ul li {
    margin: 1em;
    float: left;

}

#Note ul li h2{
  font-size:140%;
  font-weight:bold;
  padding-bottom:10px;
}

#NoteA {
  text-decoration:none;
  color:#000;
  background:#ffc;
  display:block;
  height:20em;
  width:20em;
  padding:2em;

  -moz-box-shadow:5px 5px 7px rgba(33,33,33,1);

  -webkit-box-shadow: 5px 5px 7px rgba(33,33,33,.7);

  box-shadow: 5px 5px 7px rgba(33,33,33,.7); }
#NoteA {
  -webkit-transform:rotate(-6deg);
  -o-transform:rotate(-6deg);
  -moz-transform:rotate(-6deg);
}
#Note ul li:nth-child(even) a{
  -o-transform:rotate(4deg);
  -webkit-transform:rotate(4deg);
  -moz-transform:rotate(4deg);
  position:relative;
  top:5px;
}
#Note ul li:nth-child(3n) a{
  -o-transform:rotate(-3deg);
  -webkit-transform:rotate(-3deg);
  -moz-transform:rotate(-3deg);
  position:relative;
  top:-5px;
}
#Note ul li:nth-child(5n) a{
  -o-transform:rotate(5deg);
  -webkit-transform:rotate(5deg);
  -moz-transform:rotate(5deg);
  position:relative;
  top:-10px;
}
#Note ul li a:hover,ul li a:focus{
  -moz-box-shadow:10px 10px 7px rgba(0,0,0,.7);
  -webkit-box-shadow: 10px 10px 7px rgba(0,0,0,.7);
  box-shadow:10px 10px 7px rgba(0,0,0,.7);
  -webkit-transform: scale(1.25);
  -moz-transform: scale(1.25);
  -o-transform: scale(1.25);
  position:relative;
  z-index:5;
}
#NoteA {
  text-decoration:none;
  color:#000;
  background:#ffc;
  display:block;
  height:20em;
  width:20em;
  padding:2em;
  -moz-box-shadow:5px 5px 7px rgba(33,33,33,1);
  -webkit-box-shadow: 5px 5px 7px rgba(33,33,33,.7);
  box-shadow: 5px 5px 7px rgba(33,33,33,.7);
  -moz-transition:-moz-transform .15s linear;
  -o-transition:-o-transform .15s linear;
  -webkit-transition:-webkit-transform .15s linear;
}
</style>
@endsection
