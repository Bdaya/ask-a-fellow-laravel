

<table class="table table-hover">
    <tr id="head">
        <th>Course code</th>
        <th>Course name</th>
        <th>Questions</th>
        <th>Notes</th>
        <th>Events</th>
    </tr>
    @foreach($courses as $course)
        <tr class="course_row" href="{{url('browse/'.$course->id)}}">

                <td>{{$course->course_code}}</td>
                <td>{{$course->course_name}}</td>
                <td>{{count($course->questions()->get())}}</td>
                <td>
                    <a href="{{url('browse/notes/'.$course->id)}}">View Notes</a>
                    <br>
                    <a href="{{url('/course/'.$course->id.'/uploadNote')}}">Upload Note</a>
                </td>
                <td>
                    <a href="events">View Events</a>
                    <br>
                    @if(Auth::user()->role >= 1)
                        <a href="{{url('/course/add_event/'.$course->id)}}">Add Event</a>
                    @endif
                </td>
        </tr>
    @endforeach
    <tr>
        <td>
            <a href="{{url('/browse/questions/'.$major->id.'/'.$semester)}}">View questions from all courses</a>
        </td>
    </tr>
</table>

<style>
    .table{
        box-shadow: none;
        border: 1px solid #FFAF6C;
        border-collapse: separate;
    }
    .table td
    {
        border-top: 1px solid #FFAF6C;
        cursor: pointer;
    }
    .table th
    {
        border-bottom: 1px solid #FFAF6C;
    }
</style>
<script>
    $('.course_row').click(function(){
       window.location.href = $(this).attr('href');
    });
</script>
