<?php

namespace App\Http\Controllers;

use App\AnswerReport;
use App\QuestionReport;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Major;
use App\Course;
use App\Question;
use App\Answer;
use App\Notification;
use App\Feedback;
use App\Component;
use App\ComponentCategory;
use App\Note;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Cloudinary\Uploader;
use Response;

class AppController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'post_question',
            'post_answer',
            'delete_question',
            'delete_answer',
            'view_notifications',
            'subscribe_to_courses',
            'subscription_page',
            'post_question_all',
            'add_component',
            'view_components',
            'component_details',
            'post_component'
        ]]);

    }

    public function browse()
    {
        $majors = Major::all();
        $semesters = [1,2,3,4,5,6,7,8,9];
        return view('browse.index',compact(['majors','semesters']));
    }


    public function list_questions($course_id)
    {

        $course = Course::find($course_id);
        //sort questions
        if(!$course)
            return 'Ooops! course not found';

        if(isset($_GET['page']))
            $page = $_GET['page'];
        else
            $page = 0;
        if(isset($_GET['take']))
            $take = $_GET['take'];
        else
            $take = 10;


        if($take <= 0)
            $take = 10;
        if($page <= 0)
            $page = 0;


        $questions = $course->questions()->skip($page * $take)->take($take);
        $count_questions = count($course->questions()->get());


        $order = 'latest';
        if(isset($_GET['sort']))
            $order = $_GET['sort'];
        $allowed = ['votes','oldest','latest','answers'];
        if(!in_array($order,$allowed))
            $order = 'latest';



        $questions_ordered = array();
        if($order == 'votes')
            $questions_ordered = $questions->orderBy('votes','desc')->orderBy('created_at','desc')->get();
        elseif($order == 'oldest')
            $questions_ordered = $questions->orderBy('created_at','asc')->get();
        elseif($order == 'latest')
            $questions_ordered = $questions->orderBy('created_at','desc')->get();
        else if($order == 'answers')
            $questions_ordered =$questions->orderByRaw("(SELECT COUNT(*) FROM answers WHERE question_id = questions.id) DESC")->orderBy('created_at','desc')->get();
        return view('questions.questions',compact(['questions_ordered','count_questions']));

    }


    public function list_questions_all($major_id, $semester)
    {
        $major = Major::find($major_id);
        $courses = $major->courses()->where('semester','=',$semester)->get(['courses.id','courses.course_name']);
        $ids = array();
        foreach($courses as $course)
            $ids[] = $course->id;

        if(isset($_GET['page']))
            $page = $_GET['page'];
        else
            $page = 0;
        if(isset($_GET['take']))
            $take = $_GET['take'];
        else
            $take = 10;
        if($take <= 0)
            $take = 10;
        if($page <= 0)
            $page = 0;
        $questions = Question::whereIn('course_id',$ids);
        $all = true;
        $count_questions = count($questions->get());
        $questions = $questions->skip($page * $take)->take($take);

        $order = 'latest';
        if(isset($_GET['sort']))
            $order = $_GET['sort'];
        $allowed = ['votes','oldest','latest','answers'];
        if(!in_array($order,$allowed))
            $order = 'latest';

        $questions_ordered = array();
        if($order == 'votes')
            $questions_ordered = $questions->orderBy('votes','desc')->orderBy('created_at','desc')->get();
        elseif($order == 'oldest')
            $questions_ordered = $questions->orderBy('created_at','asc')->get();
        elseif($order == 'latest')
            $questions_ordered = $questions->orderBy('created_at','desc')->get();
        else if($order == 'answers')
            $questions_ordered =$questions->orderByRaw("(SELECT COUNT(*) FROM answers WHERE question_id = questions.id) DESC")->orderBy('created_at','desc')->get();
        return view('questions.questions',compact(['questions_ordered','all','count_questions','courses']));
    }


    public function post_question_all(Request $request,$major, $semester)
    {
        $this->validate($request,[
            'question' => 'required',
            'course' => 'required|exists:courses,id'
        ]);
        $this->post_question($request,$request->course);
        return redirect('/browse/'.$major.'/'.$semester);
    }

    public function post_question(Request $request, $course_id)
    {
        $this->validate($request,[
            'question' => 'required'
        ]);
        $question = new Question;
        $question->asker_id = Auth::user()->id;
        $question->question = $request->question;
        $question->course_id = $course_id;
        $question->save();
        return redirect('/browse/'.$course_id);
    }

    public function delete_question($question_id)
    {
        $question = Question::find($question_id);
        if(Auth::user() && (Auth::user()->role > 0 ||  Auth::user()->id == $question->asker_id))
            $question->delete();
        return redirect(url('browse/'.$question->course_id));
    }



    public function inside_question($question_id)
    {

        $question = Question::find($question_id);
        if(!$question)
            return 'Ooops! question not found';
        //sort answers
        $answers = $question->answers()->get();

        return view('questions.answers',compact(['question','answers']));


    }

    public function post_answer(Request $request,$question_id)
    {
        $this->validate($request, [
            'answer' => 'required|min:5',
        ]);
        $answer = new Answer;
        $answer->answer = $request->answer;
        $answer->responder_id = Auth::user()->id;
        $answer->question_id = $question_id;
        $answer->save();

        $asker_id = Question::find($question_id)->asker_id;
        $description = Auth::user()->first_name.' '.Auth::user()->last_name.' posted an answer to your question.';
        $link = url('/answers/'.$question_id);
        Notification::send_notification($asker_id,$description,$link);
        return redirect(url('/answers/'.$question_id));
    }


    public function delete_answer($answer_id)
    {
        $answer = Answer::find($answer_id)->find($answer_id);
        if(Auth::user() && (Auth::user()->role > 0 || Auth::user()->id == $answer->responder_id))
            $answer->delete();
        return redirect(url('answers/'.$answer->question_id));
    }

    public function view_notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications;

        return view('user.notifications',compact('notifications'));

    }


    public function subscription_page()
    {
        $majors = Major::all();
        $courses = Auth::user()->subscribed_courses()->get(['courses.id']);
        $subscribed_courses = array();
        foreach($courses as $course)
            $subscribed_courses[] = $course->id;
        return view('user.subscriptions',compact(['majors','subscribed_courses']));
    }

    public function subscribe_to_courses(Request $request)
    {
        $this->validate($request,[
            'course.*' => 'numeric|exists:courses,id'
        ]);

        Auth::user()->subscribed_courses()->detach();
        if($request->course)
            Auth::user()->subscribe_to_courses(array_unique($request->course));

        return redirect('/home');
    }


    public function send_feedback(Request $request)
    {
        $this->validate($request,[
            'email' => 'email',
            'feedback' => 'required'
        ]);
        $feedback = new Feedback;
        $feedback->name = $request->name;
        $feedback->email = $request->email;
        $feedback->feedback = $request->feedback;
        $feedback->save();
        Session::flash('feedback','Feedback submitted successfully');
        return Redirect::back();
    }
    public function  list_notes($course_id)
    { //TODO : Pagination , Front end View , Offsets ,
        if(Auth::user())
        $role = Auth::user()->role;
        $course = Course::find($course_id);
        if(!$course)
           return 'Ooops! course not found';
        $notes = $course->notes;
        return view('notes.notes',compact('notes','role'));
    }

    public function view_note($note_id){
      $note = Note::find($note_id);

      $path = $note->path;

      return Response::make(file_get_contents($path), 200, [
      'Content-Type' => 'application/pdf',
      'Content-Disposition' => 'inline; filename="'.$note->title.'"'
      ]);
    }

    public function view_components()
    {
        $components = Component::all()->where('accepted',1);
        return view('user.components')->with('components',$components);
    }

    public function component_details($id)
    {
        $component = Component::find($id);
        return view('user.component_details', compact(['component']));
    }

    public function add_component(Request $request)
    {
        $category = ComponentCategory::all();
        return view('user.add_component', ['category' => $category]);
    }

    public function post_component(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|unique:components,title',
            'description' => 'required',
            'image_path' => 'image|max:1000',
            'contact_info' => 'required',
            'price' => 'numeric|min:0|max:1000000',
            'category'=>'required'
        ]);
        // seed the database first for testing
        $categories = ComponentCategory::all();
        $component = new Component;
        $component->title = $request->title;
        $component->description = $request->description;
        $component->contact_info = $request->contact_info;
        $component->price = $request->price;
        $component->category_id = $request->category;
        $component->creator_id = Auth::user()->id;
        if ($request->file('image_path')) {
            \Cloudinary::config(array(
                "cloud_name" => env("CLOUDINARY_NAME"),
                "api_key" => env("CLOUDINARY_KEY"),
                "api_secret" => env("CLOUDINARY_SECRET")
            ));
            // upload and set new picture
            $file = $request->file('image_path');
            $image = Uploader::upload($file->getRealPath(), ["width" => 300, "height" => 300, "crop" => "limit"]);
            $component->image_path = $image["url"];
        }
        $component->save();
        Session::flash('Added', 'Done, admins will review your component soon!');
        return redirect()->back();
    }
}
