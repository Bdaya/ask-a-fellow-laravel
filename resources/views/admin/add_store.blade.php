@extends('layouts.app')
@section('content')
    <style>
        table td, table th
        {
            border: 1px solid black;
            padding: 7px;
        }
    </style>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/zf/dt-1.10.11/datatables.min.css"/>

    <div class="container">
        <table class="table table-striped table-bordered" style="width:100%;" id="stores_table">
            <thead>
                <tr>
                    <th>Store Name</th>
                    <th>Store Address</th>
                    <th>Delete</th>
                    <th>Update</th>
                </tr>
            </thead>
            <tbody>
                @foreach($stores as $store)
                <tr>
                    <td>{{ $store->name }}</td>
                    <td>{{ $store->location }}</td>
                    <td><a onclick="return confirm('Are you sure?');" href="{{url('admin/delete_store/'.$store->id)}}">Delete</a></td>
                    <td><a href="{{url('admin/update_store/'.$store->id)}}">Update</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <form method="POST" action="{{url('admin/add_store')}}"  enctype="multipart/form-data" style="padding: 50px; width: 50%;">
            
            <div class="form-group">
                <label for="store_name">Store Name</label>
                <input type="text" class="form-control" id="store_name" name="store_name" placeholder="Store Name" value="{{ old('store_name') }}">
            </div>

            <div class="form-group">
                <label for="store_address">Store Address</label>
                <input type="text" class="form-control" id="store_address" name="store_address" placeholder="Store Address" value="{{ old('store_address') }}">
            </div>

            <div class="form-group">
                <label for="store_rate">Store Rate</label>
                <input type="number" min="1" max="5" class="form-control" id="store_rate" name="store_rate" placeholder="Store Rate" value="{{ old('store_rate') }}">
            </div>

            <div class="form-group">
                <label for="logoPath">Store Logo</label>
                <input name="logoPath" id="logoPath" type="file" accept="image/*">
            </div> 

            <div class="form-group">
                <label for="store_address">Store Description</label>
                <input type="text" class="form-control" id="store_description" name="store_description" placeholder="Store Description" value="{{ old('store_description') }}">
            </div>

            <div class="form-group">
                <label for="store_phone_number">Store Phone Number</label>
                <input type="text" class="form-control" id="store_phone_number" name="store_phone_number" placeholder="Store Phone Number" value="{{ old('store_phone_number') }}">
            </div>

            <button type="submit" class="btn btn-default">Add Store</button>
            <br></br>
            <div class="error" style="color:red">
                @include('errors')
            </div>
            @if (Session::has('Added'))
                <div class="alert alert-info">{{ Session::get('Added') }}</div>
            @endif
            @if (Session::has('Updated'))
                <div class="alert alert-info">{{ Session::get('Updated') }}</div>
            @endif
        </form>
    </div>

    <script type="text/javascript" src="https://cdn.datatables.net/t/zf/dt-1.10.11/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#stores_table').DataTable();
        });
    </script>

    <style>
        #stores_table_wrapper
        {
            width: 70%;
        }

        .odd {
            background-color: #FFECDC !important;
        }

        #stores_table thead tr {
            background-color: #FFCEA5;
        }
    </style>
@endsection