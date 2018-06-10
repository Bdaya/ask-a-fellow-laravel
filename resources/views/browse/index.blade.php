@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ url('/css/main.css') }}" />
      
      <div class="container" style="text-align: center; width: 100%;">
        <div class="major_and_semester center-block">
            <ul class="buttons-list__list">
              <li class="buttons-list__item">
                <select class="buttons-list__btn btn" name="major" id="major">
                    <option value="">Select a major</option>
                    @foreach($majors as $major)
                        <option value="{{$major->id}}">{{$major->major}}</option>
                    @endforeach
                </select>
              </li>
              <li class="buttons-list__item">
                <select class="buttons-list__btn btn" name="semester" id="semester">
                    <option value="">Select a semester</option>
                    @foreach($semesters as $semester)
                        <option value="{{$semester}}">{{$semester}}</option>
                    @endforeach
                </select>
              </li>
            </ul>
            <a id="show_courses" style="margin-top:20px; margin-bottom:30px;"href="#" class="btn btn-warning"><strong>Show Courses</strong></a>
        </div>

        <div class="courses pull-right">

        </div>
    </div>

<script>
    $('#show_courses').click(function(){
        //send ajax request;
        var major = $('#major').val();
        var semester = $('#semester').val();
        var url = "{{ url('/list_courses') }}";
        $.ajax({
            url: url+'/'+major+'/'+semester,
            success: function(data){
                $('.courses').html(data);

            }

        });
    });
</script>

@endsection
