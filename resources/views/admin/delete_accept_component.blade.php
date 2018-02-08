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
        <table class="table table-striped table-bordered" style="width:100%;" id="component_table">
            <thead>
            <tr>
                <th>Component title</th>
                <th>Delete</th>
                <th>Accept</th>
                <th>Reject</th>
            </tr>
            </thead>
            <tbody>
            @foreach($components as $component)
                <tr>
                    <td>{{$component->title}}</td>
                    @if ($component->accepted == 0)
                        <td></td>
                    @else
                        <td><a onclick="return confirm('Are you sure?');" href="{{url('admin/delete_component/'.$component->id)}}">Delete</a></td>
                    @endif
                    
                    @if ($component->accepted == 0)
                        <td><a href="{{url('admin/accept_component/'.$component->id)}}">Accept</a></td>
                    @else
                        <td>Accepted</td>
                    @endif
                    
                    @if ($component->accepted == 0)
                        <td><a onclick="return confirm('Component will get deleted and you will be redirected to send an email to the creator\nAre you sure?');" href="{{url('admin/reject_component/'.$component->id)}}">Reject</a></td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <script type="text/javascript" src="https://cdn.datatables.net/t/zf/dt-1.10.11/datatables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#component_table').DataTable();
        } );
    </script>

    <style>
        #component_table_wrapper
        {
            width: 70%;
        }
        .odd {
            background-color: #FFECDC !important;
        }

        #component_table thead tr {
            background-color: #FFCEA5;
        }
    </style>
@endsection