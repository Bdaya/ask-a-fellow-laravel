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

            </div>
        </div>
    </div>
</div>

@endsection

