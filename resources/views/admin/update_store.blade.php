@extends('layouts.app')
@section('content')
    <div class="container">
        <form method="POST" action="{{url('admin/update_store/'.$store->id)}}"  enctype="multipart/form-data" style="padding: 50px; width: 50%;">
            
            <div class="form-group">
                <label for="store_name">Store Name</label>
                <input type="text" class="form-control" id="store_name" name="store_name" value="{{$store->name}}">
            </div>

            <div class="form-group">
                <label for="store_address">Store Address</label>
                <input type="text" class="form-control" id="store_address" name="store_address" value="{{$store->location}}">
            </div>

            <div class="form-group">
                <label for="store_rate">Store Rate</label>
                <input type="number" min="1" max="5" class="form-control" id="store_rate" name="store_rate" value="{{$store->rate_count}}">
            </div>

            <div class="form-group">
                <label for="logoPath">Store Logo</label>
                <input type="file" id="logoPath" name="logoPath" accept="image/*">
            </div> 

            <div class="form-group">
                <label for="store_address">Store Description</label>
                <input type="text" class="form-control" id="store_description" name="store_description" value="{{$store->description}}">
            </div>

            <div class="form-group">
                <label for="store_phone_number">Store Phone Number</label>
                <input type="text" class="form-control" id="store_phone_number" name="store_phone_number" value="{{$store->phone}}"">
            </div>

            <button type="submit" class="btn btn-default">Update Store</button>
            <br></br>
            <div class="error" style="color:red">
                @include('errors')
            </div>
        </form>
    </div>
@endsection