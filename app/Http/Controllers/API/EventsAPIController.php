<?php

namespace App\Http\Controllers\API;

use App\Course;
use App\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class EventsAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'create'
        ]]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'title' => 'required|max:255',
            'description' => 'required',
            'date' => 'required',
            'place' => 'required',
        ]);
    }

    /**
     * Return a set of events associated with a course
     *
     * @param $course_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function index($course_id)
    {
        $course = Course::find($course_id);
        if (!$course) {
            return response()->json(['status' => '404 not found', 'message' => 'Course not found'], 404);
        }

        $events = $course->events()->where('verified', 1)->paginate(10);
        return response()->json($events, 200);
    }

    public function show($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json(['status' => '404 not found', 'message' => 'Event not found']);
        }

        $announcements = $event->announcements()->orderBy('updated_at')->paginate(5);
        $returnedData = [];
        $returnedData['status'] = '200 ok';
        $returnedData['error'] = null;
        $returnedData['data'] = [];
        $returnedData['data']['event'] = $event;
        $returnedData['data']['event']['announcements'] = $announcements;

        return response()->json($returnedData, 200);
    }

    /**
     * Create a new event of a specific course
     * @param $course_id
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create($course_id, Request $request)
    {
        $course = Course::find($course_id);
        if (!$course) {
            return response()->json(['status' => '404 not found', 'message' => 'Course not found'], 404);
        }
        $validator = $this->validator($request->all());

        if ($validator->fails())
            return response()->json($validator->errors(), 302);

        $event = new Event($request->all());
        $event['course_id'] = $course_id;
        $event['creator_id'] = Auth::user()->id;
        $event->save();
        return response()->json($event, 200);
    }

    public function deleteEvent($id)
    {
        $event = Event::find($id);
        $event->delete();
        return response()->json(['status' => '200 ok', 'message' => 'Your request to delete this event is successful'], 200);
    }

}
