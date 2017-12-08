<?php

namespace App\Http\Controllers\API;

use App\Course;
use App\Event;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventsAPIController extends Controller
{
    /**
     * Return a set of events associated with a course
     *
     * @param $course_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($course_id)
    {
        $course = Course::find($course_id);
        $events = $course->events()->where('verified', true)->paginate(10);

        return response()->json($events, 200);
    }
}
