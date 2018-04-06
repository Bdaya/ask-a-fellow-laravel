@extends('layouts.app')
@section('content')

<div class="container" style="padding-left: 100px; padding-right: 100px;">
        <h1>Upload Notes</h1>
        <br>
        <form class="form-horizontal" style="width: 80%;" method="POST" action="" enctype="multipart/form-data">
            {{  csrf_field() }}
            <div class="form-group">
                <label for="title" class="col-sm-3 control-label">Title</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="Title" name="title" value="{{ old('title') }}" placeholder="Note Title">
                </div>
            </div>
            <div class="form-group">
                <label for="description" class="col-sm-3 control-label">Description</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}" placeholder="Note Description">
                </div>
            </div>
            <div class="form-group">
                <label for="file" class="col-sm-3 control-label">File</label>
                <div class="col-sm-7">
                    <input name="file" id="file" type="file">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-10">
                    <button type="submit" class="btn btn-default">Upload</button>
                </div>
            </div>
            <div class="errors" style="color:red">
            @include('errors')
            </div>
            <br>
            @if (Session::has('success'))
                <div class="alert alert-info">{{ Session::get('success') }}</div>
            @endif
        </form>
    </div>



@endsection
