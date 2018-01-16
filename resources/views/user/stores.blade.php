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
                <th>Rate</th>
                <th>Phone Number</th>
                <th>Location</th>
            </tr>
            </thead>
            <tbody>
                @foreach($stores as $store)
                <tr>
                    <td><a href="/user/stores/{{ $store->id }}">{{ $store->name }}</a></td>
                    <td>{{ $store->rate_count }}</td>
                    <td>{{ $store->phone}}</td>
                    <td>{{ $store->location }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
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