<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Event;
use App\Announcement;
use App\Http\Requests;
use App\Course;
use Illuminate\Support\Facades\Redirect;
use Auth;
use App\Notification;
use Mail;

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

    public function deleteEvent($id)
    {
        $event = Event::find($id);
        $event->delete();
        return Redirect::back();
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

        //notify users with the new announcement
         //notify users with the new announcement
        $event_id = $announcement->event_id;
        $event = Event::find($event_id);
        $users = $event->course->subscribed_users;
        $mail_subject = 'New Event: '.$event->title;
        $link = url('/events/'.$event->id);
        $course_name = Course::find($event->course_id)->course_name;
        $details = 'You have 1 new announcement titled: '.$announcement->title.' related to event: '.$event->title.' in your subscribed course: '.$course_name;
        $usersIDs = [];
        $usersEmails = [];
        $event_announcement = 'announcement';
        $url = 'http://localhost:8000/events/'.$event_id;

        foreach ($users as $user) {
            Notification::send_notification($user->id,$details,$link);
            $usersIDs[] = $user->id;
            $usersEmails[] = $user->email;
        }

        $sendMail = Mail::send('admin.emails.event_notification', ['event_announcement' => $event_announcement, 'details' => $details, 'url' => $url], function ($message) use ($usersEmails, $mail_subject) {
            $message->to([])->bcc($usersEmails)
                ->subject($mail_subject);
        });

        if (!$sendMail) {
            Session::flash('error', 'Error while notifying users subscribed to event course!');
        }

        return Redirect::back();
    }
}
