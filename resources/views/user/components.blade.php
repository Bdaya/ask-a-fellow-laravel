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
        <table class="table table-striped table-bordered" style="width:100%;" id="components_table">
            <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Price</th>
            </tr>
            </thead>
            <tbody>
                @foreach($components as $component)
                <tr>
                    <td><a href="/user/components/{{ $component->id }}">{{ $component->title }}</a></td>
                    <td>{{ $component->category()->name }}</td>
                    <td>{{ $component->price}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <script type="text/javascript" src="https://cdn.datatables.net/t/zf/dt-1.10.11/datatables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#components_table').DataTable();
        });
    </script>

    <style>
        #components_table_wrapper
        {
            width: 70%;
        }

        .odd {
            background-color: #FFECDC !important;
        }

        #components_table thead tr {
            background-color: #FFCEA5;
        }
    </style>
@endsection