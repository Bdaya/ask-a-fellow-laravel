<?php

namespace App\Http\Controllers;

use App\AdminMail;
use App\Announcement;
use App\Answer;
use App\AnswerReport;
use App\Component;
use App\ComponentCategory;
use App\Course;
use App\Event;
use App\Feedback;
use App\Major;
use App\Note;
use App\Notification;
use App\Question;
use App\QuestionReport;
use App\Store;
use App\User;
use App\VerifiedUsersCourses;
use Auth;
use Cloudinary\Uploader;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Mail;
use Session;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth_admin');
    }

    public function index()
    {
        return view('admin.list');
    }

    public function add_course_page()
    {
        $courses = Course::all();
        $majors = Major::all();

        return view('admin.add_course', compact(['courses', 'majors']));
    }

    public function add_course(Request $request)
    {
        $this->validate($request, [
            'course_code' => 'alpha_num|required',
            'course_name' => 'required',
            'semester' => 'numeric|between:1,10|required',
            'majors.*' => 'numeric|exists:majors,id',
        ]);

        $course = new Course();
        $course->course_code = $request->course_code;
        $course->course_name = $request->course_name;
        $course->semester = $request->semester;
        $course->save();
        $course->majors()->attach($request->majors);
        Session::flash('Added', 'New course is added successfully!');
        return redirect('admin/add_course');
    }

    public function delete_course($id)
    {
        $course = Course::find($id);
        $course->delete();
        return redirect('/admin/add_course');
    }

    public function update_course_page($id)
    {
        $course = Course::find($id);
        $majors = Major::all();

        $course_majors = array();
        foreach ($course->majors()->get() as $major) {
            $course_majors[] = $major->id;
        }

        return view('admin.update_course', compact(['course', 'majors', 'course_majors']));
    }

    public function update_course($id, Request $request)
    {
        $this->validate($request, [
            'course_code' => 'alpha_num|required',
            'course_name' => 'required',
            'semester' => 'numeric|between:1,10|required',
            'majors.*' => 'numeric|exists:majors,id',
        ]);

        $course = Course::find($id);
        $course->course_code = $request->course_code;
        $course->course_name = $request->course_name;
        $course->semester = $request->semester;
        $course->save();
        $course->majors()->detach();
        $course->majors()->attach($request->majors);
        Session::flash('Updated', 'Course is updated!');
        return redirect('admin/add_course');
    }

    public function add_major_page()
    {
        $majors = Major::all();
        return view('admin.add_major', compact(['majors']));
    }

    public function add_major(Request $request)
    {
        $this->validate($request, [
            'faculty' => 'required',
            'major' => 'required',
        ]);
        $major = new Major();
        $major->faculty = $request->faculty;
        $major->major = $request->major;
        $major->save();
        Session::flash('Added', 'New major is added!');
        return redirect('/admin/add_major');
    }

    public function delete_major($id)
    {
        $major = Major::find($id);
        $major->delete();
        return redirect('/admin/add_major');
    }

    public function update_major_page($id)
    {
        $major = Major::find($id);
        return view('admin.update_major', compact(['major']));
    }

    public function update_major($id, Request $request)
    {
        $this->validate($request, [
            'faculty' => 'required',
            'major' => 'required',
        ]);

        $major = Major::find($id);
        $major->faculty = $request->faculty;
        $major->major = $request->major;
        $major->save();
        Session::flash('Updated', 'Major is updated!');
        return redirect('admin/add_major');
    }

    public function add_component_category_page()
    {
        $categories = ComponentCategory::all();
        return view('admin.add_component_category')->with('categories', $categories);
    }

    public function add_component_category(Request $request)
    {
        $this->validate($request, [
            'category_name' => 'required|unique:component_categories,name',
        ]);
        $category = new ComponentCategory();
        $category->name = $request->category_name;
        $category->save();
        Session::flash('Added', 'New component category is added!');
        return redirect('/admin/add_component_category');
    }

    public function delete_component_category($id)
    {
        $category = ComponentCategory::find($id);
        $category->delete();
        return redirect('/admin/add_component_category');
    }

    public function update_component_category_page($id)
    {
        $category = ComponentCategory::find($id);
        return view('admin.update_component_category', compact(['category']));
    }

    public function update_component_category($id, Request $request)
    {
        $this->validate($request, [
            'category_name' => 'required|unique:component_categories,name',
        ]);

        $category = ComponentCategory::find($id);
        $category->name = $request->category_name;
        $category->save();
        Session::flash('Updated', 'Component category is updated!');
        return redirect('admin/add_component_category');
    }

    public function delete_accept_component_page()
    {
        $components = Component::all();
        return view('admin.delete_accept_component', compact(['components']));
    }

    public function delete_component($id)
    {
        $component = Component::find($id);
        $component->delete();
        return redirect('/admin/delete_accept_component');
    }

    public function accept_component($id)
    {
        $component = Component::find($id);
        $component->accepted = 1;
        $component->save();
        return redirect('admin/delete_accept_component');
    }

    public function reject_component($id)
    {
        $component = Component::find($id);
        $component->delete();
        $creator_id = $component->creator_id;
        return redirect('/admin/mail/one/' . $creator_id);
    }

    // Announcements Controller

    public function add_announcement_page()
    {
        $events = Event::where('verified', 1)->paginate(6);
        return view('admin.add_announcement', compact('events'));
    }

    public function add_announcement(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'event' => 'required',
            'description' => 'required',
        ]);

        $announcement = new Announcement();

        $announcement['user_id'] = Auth::user()->id;
        $announcement['title'] = $request['title'];
        $announcement['event_id'] = $request['event'];
        $announcement['description'] = $request['description'];

        $announcement->save();

        //notify users with the new announcement
        $event_id = $announcement->event_id;
        $event = Event::find($event_id);
        $users = $event->course->subscribed_users;
        $mail_subject = 'New Event: ' . $event->title;
        $link = url('/events/' . $event->id);
        $course_name = Course::find($event->course_id)->course_name;
        $details = 'You have 1 new announcement titled: ' . $announcement->title . ' related to event: ' . $event->title . ' in your subscribed course: ' . $course_name;
        $usersIDs = [];
        $usersEmails = [];
        $event_announcement = 'announcement';
        $url = 'http://localhost:8000/events/' . $event_id;

        foreach ($users as $user) {
            Notification::send_notification($user->id, $details, $link);
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

    // Events Controller
    public function add_event_page()
    {
        $courses = Course::all();

        return view('admin.add_event', compact(['courses']));
    }

    public function add_event(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'course' => 'required',
            'date' => 'required',
            'place' => 'required',
            'description' => 'required',
        ]);

        $event = new Event();

        $event['creator_id'] = Auth::user()->id;
        $event['title'] = $request['title'];
        $event['course_id'] = $request['course'];
        $event['date'] = $request['date'];
        $event['place'] = $request['place'];
        $event['description'] = $request['description'];

        if (Auth::user()->role == 1) {
            $event['verified'] = 1;
        }

        $event->save();

        $event_id = $event->id;
        $users = $event->course->subscribed_users;
        $mail_subject = 'New Event: ' . $event->title;
        $link = url('/events/' . $event->id);
        $course_name = Course::find($event->course_id)->course_name;
        $details = 'You have 1 new event titled: ' . $event->title . ' related to your subscribed course: ' . $course_name;
        $usersIDs = [];
        $usersEmails = [];
        $event_announcement = 'event';
        $url = 'http://localhost:8000/events/' . $event_id;

        foreach ($users as $user) {
            Notification::send_notification($user->id, $details, $link);
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

        Session::flash('Added', 'Done, Event is added successfully!');

        return Redirect::back();
    }

    public function view_feedbacks()
    {
        $feedbacks = Feedback::all();
        return view('admin.feedbacks', compact(['feedbacks']));
    }

    public function view_reports()
    {
        $question_reports = QuestionReport::all();
        $answer_reports = AnswerReport::all();
        return view('admin.reports', compact(['question_reports', 'answer_reports']));
    }

    public function manyMailView()
    {
        $users = User::where('confirmed', '>=', '1')->get();
        return view('admin.mail_many', compact(['users']));
    }

    public function oneMailView($id)
    {
        $user = User::find($id);
        return view('admin.mail_one', compact(['user']));
    }

    public function processMailToUsers(Request $request, $type)
    {
        if ($type == 0) {
            $sendMail = $this->sendMailToOneUser($request->user_id, $request->mail_subject, $request->mail_content);
            if ($sendMail) {
                Session::flash('mail', 'Mail sent successfully');
                return redirect(url('user/' . $request->user_id));
            } else {
                Session::flash('mail', 'Error sending mail');
                return redirect(url('admin/mail/one/' . $request->user_id));
            }
        } else {
            $sendMail = $this->sendMailToManyUsers($request->users, $request->mail_subject, $request->mail_content);
            if ($sendMail) {
                Session::flash('mail', 'Mail sent successfully');
                return redirect(url('admin/'));
            } else {
                Session::flash('mail', 'Error sending mail');
                return redirect(url('admin/mail/many/'));
            }
        }
    }

    public function sendMailToOneUser($user_id, $mail_subject, $mail_content)
    {
        $user = User::find($user_id);

        $sendMail = Mail::send('admin.emails.general', ['mail_content' => $mail_content, 'name' => $user->first_name], function ($message) use ($user, $mail_subject, $mail_content) {
            $message->to($user->email, $user->first_name)
                ->subject($mail_subject);
        });
        if ($sendMail) {
            $this->saveMail([$user_id], $mail_subject, $mail_content);
        }

        return $sendMail;
    }

    public function sendMailToManyUsers($users, $mail_subject, $mail_content)
    {
        $usersEmails = [];
        foreach ($users as $user) {
            $usersEmails[] = User::find($user)->email;
        }

        $sendMail = Mail::send('admin.emails.general', ['mail_content' => $mail_content, 'name' => 'awesome AskaFellow member'], function ($message) use ($usersEmails, $mail_subject, $mail_content) {
            $message->to([])->bcc($usersEmails)
                ->subject($mail_subject);
        });

        if ($sendMail) {
            $this->saveMail($users, $mail_subject, $mail_content);
        }

        return $sendMail;
    }

    public function saveMail($recipients, $mail_subject, $mail_body)
    {
        $mail = new AdminMail();
        $mail->user_id = Auth::user()->id;
        $mail->subject = $mail_subject;
        $mail->body = $mail_body;
        $mail->save();
        $mail->recipients()->attach($recipients);
    }

    public function showMailLog()
    {
        $mails = AdminMail::orderBy('created_at', 'desc')->get();
        return view('admin.mail_log', compact(['mails']));
    }

    public function listUsers()
    {
        $users = User::orderBy('first_name', 'asc');
        return view('admin.users', compact(['users']));
    }

    public function add_badge()
    {
        $users = User::orderBy('first_name', 'asc');
        $courses = Course::all()->sortBy('course_code');
        $verified_users_courses = VerifiedUsersCourses::all();
        return view('admin.badge', compact(['users', 'courses', 'verified_users_courses']));
    }

    public function save_badge($id)
    {
        $user = User::findOrFail($id);
        $user->verified_badge = 1;
        $user->save();
        $users = User::orderBy('first_name', 'asc');
        $users = User::orderBy('first_name', 'asc');
        return Redirect::back()->with('message','Operation Successful !');
    }

    public function remove_badge($id)
    {
        $user = User::findOrFail($id);
        $user->verified_badge = 0;
        $user->save();
        return Redirect::back()->with('message','Operation Successful !');
    }

    public function verified_add_remove_course($id)
    {
        if (Input::get('add_course') !== null) {
            $verified_users_courses = new VerifiedUsersCourses();
            $verified_users_courses->user_id = $id;
            $verified_users_courses->course_id = Input::get('item_id');
            $verified_users_courses->save();
        }else{
            VerifiedUsersCourses::where('user_id', $id)->where('course_id', Input::get('item_id'))->delete();
        }
        return Redirect::back()->with('message','Operation Successful !');

    }

    public function statistics()
    {
        $questions = Question::all()->count();
        $answers = Answer::all()->count();
        $users = User::all()->count();
        return view('admin.statistics', compact(['questions', 'answers', 'users']));
    }

    //function to view all event requests
    public function eventRequests()
    {
        $requests = Event::all()->where('verified', 0);
        return view('admin.event_requests')->with('requests', $requests);
    }

    //to view information about the clicked event and its creator with the option to accept or delete it
    public function viewRequest($id)
    {
        $event = Event::find($id);
        $creator = User::find($event->creator_id);
        //return $event;
        return view('admin.event', compact(['event', 'creator']));
    }

    //function to reject event requests by searching and removing it from the database
    public function rejectRequest($id)
    {
        $event = Event::find($id);
        $event->delete();
        return redirect('admin/event_requests');
    }

    //function to accept event requests by searching and setting its verified flag to true
    public function acceptRequest($id)
    {
        $event = Event::Find($id);
        $event->verified = 1;
        $event->save();

        //notify users with the new event
        $event_id = $event->id;
        $users = $event->course->subscribed_users;
        $mail_subject = 'New Event: ' . $event->title;
        $link = url('/events/' . $event->id);
        $course_name = Course::find($event->course_id)->course_name;
        $details = 'You have 1 new event titled: ' . $event->title . ' related to your subscribed course: ' . $course_name;
        $usersIDs = [];
        $usersEmails = [];
        $event_announcement = 'event';
        $url = 'http://localhost:8000/events/' . $event_id;

        foreach ($users as $user) {
            Notification::send_notification($user->id, $details, $link);
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

        return redirect('admin/event_requests');
    }

    public function add_store_page()
    {
        $stores = Store::all();
        return view('admin.add_store')->with('stores', $stores);
    }

    public function add_store(Request $request)
    {
        $this->validate($request, [
            'store_name' => 'required',
            'store_address' => 'required',
            'store_rate' => 'required',
            'logoPath' => 'image|max:1000',
            'store_description' => 'required',
            'store_phone_number' => 'required',
        ]);
        $store = new Store();
        $store->name = $request->store_name;
        $store->location = $request->store_address;
        $store->rate_count = $request->store_rate;
        if ($request->file('logoPath')) {
            \Cloudinary::config(array(
                "cloud_name" => env("CLOUDINARY_NAME"),
                "api_key" => env("CLOUDINARY_KEY"),
                "api_secret" => env("CLOUDINARY_SECRET"),
            ));
            // upload and set new picture
            $file = $request->file('logoPath');
            $image = Uploader::upload($file->getRealPath(), ["width" => 300, "height" => 300, "crop" => "limit"]);
            $store->logo = $image["url"];
        }
        $store->description = $request->store_description;
        $store->phone = $request->store_phone_number;
        $store->save();
        Session::flash('Added', 'Store is added successfully!');
        return redirect('admin/add_store');
    }

    public function delete_store($id)
    {
        $store = Store::find($id);
        $store->delete();
        return redirect('admin/add_store');
    }

    public function update_store_page($id)
    {
        $store = Store::find($id);
        return view('admin.update_store')->with('store', $store);
    }

    public function update_store($id, Request $request)
    {
        $this->validate($request, [
            'store_name' => 'required',
            'store_address' => 'required',
            'store_rate' => 'required',
            'logoPath' => 'image|max:1000',
            'store_description' => 'required',
            'store_phone_number' => 'required',
        ]);
        $store = Store::find($id);
        $store->name = $request->store_name;
        $store->location = $request->store_address;
        $store->rate_count = $request->store_rate;
        if ($request->file('logoPath')) {
            \Cloudinary::config(array(
                "cloud_name" => env("CLOUDINARY_NAME"),
                "api_key" => env("CLOUDINARY_KEY"),
                "api_secret" => env("CLOUDINARY_SECRET"),
            ));
            // upload and set new picture
            $file = $request->file('logoPath');
            $image = Uploader::upload($file->getRealPath(), ["width" => 300, "height" => 300, "crop" => "limit"]);
            $store->logo = $image["url"];
        }
        $store->description = $request->store_description;
        $store->phone = $request->store_phone_number;
        $store->save();
        Session::flash('Updated', 'Store is updated successfully!');
        return redirect('admin/add_store');
    }

    //function to get all node upload requests
    public function noteRequests()
    {
        $notes_upload = DB::table('notes')->where('notes.request_upload', '=', 1)
            ->join('users', 'notes.user_id', '=', 'users.id')
            ->join('courses', 'notes.course_id', '=', 'courses.id')
            ->select('notes.*', 'users.first_name', 'users.last_name', 'courses.course_name', 'courses.course_code')
            ->get();
        $notes_delete = DB::table('notes')->where('notes.request_delete', '=', 1)
            ->join('users', 'notes.user_id', '=', 'users.id')
            ->join('courses', 'notes.course_id', '=', 'courses.id')
            ->select('notes.*', 'users.first_name', 'users.last_name', 'courses.course_name', 'courses.course_code')
            ->get();

        return view('admin.upload_delete_requests', compact(['notes_upload', 'notes_delete']));
    }

    //approved the uplaod of a note by changing its request_upload status to 0
    public function approveNoteUpload($id)
    {
        $note = Note::find($id);
        $note->request_upload = false;
        $note->save();
        return redirect('admin/note_requests');
    }

    //deletes note using its ID
    public function deleteNote($id)
    {
        $note = Note::find($id);
        $disk = Storage::disk('google');
        $file = collect($disk->listContents())->where('type', 'file')
            ->where('extension', pathinfo($note->path, PATHINFO_EXTENSION))
            ->where('filename', pathinfo($note->path, PATHINFO_FILENAME))->first();
        $disk->delete($file['path']);
        $note->delete();

        return redirect('admin/note_requests');
    }

    public function reject_note_delete($id)
    {
        $note = Note::find($id);
        $note->request_delete = false;
        $note->comment_on_delete = "";
        $note->save();
        return redirect('admin/note_requests');
    }

    //Function to Delete the note as an admin
    public function deleteNoteAdmin($id)
    {
        if (Auth::user()) {
            $role = Auth::user()->role;

            if ($role == 1) {
                $note = Note::find($id);
                $disk = Storage::disk('google');
                $file = collect($disk->listContents())->where('type', 'file')
                    ->where('extension', pathinfo($note->path, PATHINFO_EXTENSION))
                    ->where('filename', pathinfo($note->path, PATHINFO_FILENAME))->first();
                $disk->delete($file['path']);
                $course = $note->course->id;
                $note->delete();
                return redirect('/browse/notes/' . $course);

            } else {
                return Redirect::back();
            }
        }
    }

    public function viewNote($id)
    {
        $note = Note::find($id);
        $disk = Storage::disk('google');
        $file = collect($disk->listContents())->where('type', 'file')
            ->where('extension', pathinfo($note->path, PATHINFO_EXTENSION))
            ->where('filename', pathinfo($note->path, PATHINFO_FILENAME))->first();

        return response()->redirectTo('https://drive.google.com/file/d/' . $file['path'] . '/view');
    }
}
