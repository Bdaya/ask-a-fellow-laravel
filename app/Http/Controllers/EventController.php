<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Event;
use App\Announcement;
use App\Http\Requests;
use App\Course;
use Illuminate\Support\Facades\Redirect;
use Auth;

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

    public function add_announcement(Request $request, $event_id)
    {
        $this->validate($request, [
          'title' => 'required',
          'description' => 'required'
      ]);

        $announcement = new Announcement();

        $announcement['user_id'] = Auth::user()->id;
        $announcement['title'] = $request['title'];
        $announcement['event_id'] = $event_id;
        $announcement['description'] = $request['description'];

        $announcement->save();

        return Redirect::back();
    }
}
