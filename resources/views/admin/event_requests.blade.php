@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">

              <div class="panel-heading">
              <h1>Event Requests</h1>
              </div>

              <div class="panel-body">
               @foreach($requests as $request)
               <ul>
               <h3></h3>
                <a href="/admin/request/{{ $request->id }}">{{ $request->title }}</a>
                <br>
                </ul>
               @endforeach
              </div>
          	
        </div>
    </div>
</div>

@if (Session::has('error'))
    <div class="alert alert-danger">{{ Session::get('error') }}</div>
  @endif

@endsection