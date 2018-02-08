@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">

              <div class="panel-heading">
                <h1>Store Details</h1>
              </div>

              <div class="panel-body">
                <img src="{{asset($store->logo)}}" alt="No Logo Found!" width="100" height="100">
                <h2 align="center">{{ $store->name }}</h2>
                <br>
                <h3>Rate: {{ $store->rate }}</h3>
                <h3>Description: {{ $store->description }}</h3>
                <h3>Location: {{ $store->location }}</h3>
                <h3>Phone Number: {{ $store->phone }}</h3>
                <h3>Review: {{ $store->review }}</h3>
              </div>

            </div>
        </div>
    </div>
</div>

@endsection

