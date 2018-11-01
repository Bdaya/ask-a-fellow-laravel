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
        <table class="table table-striped table-bordered" style="width:100%;" id="categories_table">
            <thead>
            <tr>
                <th>Category name</th>
                <th>Delete</th>
                <th>Update</th>
            </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
                <tr>
                    <td>{{$category->name}}</td>
                    <td><a onclick="return confirm('Are you sure?');" href="{{url('admin/delete_component_category/'.$category->id)}}">Delete</a></td>
                    <td><a href="{{url('admin/update_component_category/'.$category->id)}}">Update</a></td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <form method="POST" action="{{url('admin/add_component_category')}}" style="padding: 50px; width: 50%;">
            {{csrf_field()}}
            <div class="form-group">
                <label for="category_name">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Category Name" value="{{ old('category_name') }}">
            </div>
            <button type="submit" class="btn btn-default">Add Category</button>
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
        $(document).ready( function () {
            $('#categories_table').DataTable();
        } );
    </script>

    <style>
        #categories_table_wrapper
        {
            width: 70%;
        }
        .odd {
            background-color: #FFECDC !important;
        }

        #categories_table thead tr {
            background-color: #FFCEA5;
        }
    </style>
@endsection