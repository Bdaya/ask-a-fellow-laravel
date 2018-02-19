@extends('layouts.app')
@section('content')
    <style>
        table td, table th
        {
            border: 1px solid black;
            padding: 7px;
            max-width: 300px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/t/zf/dt-1.10.11/datatables.min.css"/>
    <div class="container">
        <table class="table table-striped table-bordered" style="width:100%;" id="majors_table">
            <thead>
            <tr>
                <th>Faculty</th>
                <th>Major Name</th>
                <th>Courses</th>`
                <th>Delete</th>
                <th>Update</th>

            </tr>
            </thead>
            <tbody>
            @foreach($majors as $major)
                <tr>
                    <td>{{$major->faculty}}</td>
                    <td>{{$major->major}}</td>
                    <td>
                        <ul>
                            @foreach($major->courses()->get() as $course)
                                <li>{{$course->course_name}}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td><a onclick="return confirm('Are you sure?');" href="{{url('admin/delete_major/'.$major->id)}}">Delete</a></td>
                    <td><a href="{{url('admin/update_major/'.$major->id)}}">Update</a></td>
                </tr>

            @endforeach
            </tbody>
        </table>
        <form method="POST" action="{{url('admin/add_major')}}" style="padding: 50px; width: 50%;">
            {{csrf_field()}}
            <div class="form-group">
                <label for="faculty">Faculty</label>
                <input type="text" class="form-control" id="faculty" name="faculty" placeholder="Faculty" value="{{ old('faculty') }}">
            </div>
            <div class="form-group">
                <label for="major">Major Name</label>
                <input type="text" class="form-control" id="major" name="major" placeholder="Major" value="{{ old('major') }}">
            </div>
            <button type="submit" class="btn btn-default">Add Major</button>
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
            $('#majors_table').DataTable();
        } );
    </script>

    <style>
        #majors_table_wrapper
        {
            width: 70%;
        }
        .odd {
            background-color: #FFECDC !important;
        }

        #majors_table thead tr {
            background-color: #FFCEA5;
        }
    </style>
@endsection