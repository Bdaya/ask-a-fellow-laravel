<?php

namespace App\Http\Controllers;

use App\Event;
use App\Http\Requests;
use App\Course;

class EventController extends Controller
{
    /**
     * Show All the events
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $events = Event::where('verified', 1)->paginate(6);
        return view('events.index', compact('events'));
    }

    /**
     * Show a specific event
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        $announcements = $event->announcements()->orderBy('updated_at')->get();
        return view('events.show', compact('event', 'announcements'));
    }

    public function add_event_page($course_id)
    {
        $courses = [Course::find($course_id)];
        
        return view('admin.add_event', compact(['courses']));
    }
}
