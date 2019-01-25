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
use App\ComponentAnswer;
use App\ComponentCategory;
use App\ComponentQuestion;
use App\BookmarkComponentQuestion;
use App\BookmarkQuestion;
use App\Note;
use App\VerifiedUsersCourses;
use Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
            'post_component',
            'post_component_question',
            'post_component_answer',
            'view_component_answers',
            'delete_component_question',
            'bookmark_component_question',
            'bookmark_question',
            'delete_component_answer'
        ]]);

    }

    public function browse()
    {
        $majors = Major::all();
        $semesters = [1,2,3,4,5,6,7,8,9,10];
        return view('browse.index',compact(['majors','semesters']));
    }


    public function list_questions($course_id)
    {
        $verified_users_courses = null;
        if (Auth::check())
            $verified_users_courses = VerifiedUsersCourses::where('course_id', $course_id)->where('user_id', Auth::user()->id)->get();
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
        return view('questions.questions',compact(['questions_ordered','count_questions', 'verified_users_courses']));

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
        $file = $request->file('file');
        if($file){
            $fileName = 'question_'.time().'_'.$file->getClientOriginalName();
            $mainDisk = Storage::disk('google');
            $mainDisk->put($fileName, fopen($file, 'r+'));
            $question->attachement_path = $fileName;
        }
        $question->save();
        return redirect('/browse/'.$course_id);
    }

    public function delete_question($question_id, $verified_users_courses)
    {
        $question = Question::find($question_id);
        if(Auth::user() && (Auth::user()->role > 0 ||  Auth::user()->id == $question->asker_id || $verified_users_courses !== null)){
            if($question->attachement_path){
                $disk = Storage::disk('google');
                $file = collect($disk->listContents())->where('type', 'file')
                        ->where('extension', pathinfo($question->attachement_path, PATHINFO_EXTENSION))
                        ->where('filename', pathinfo($question->attachement_path, PATHINFO_FILENAME))->first();
                $disk->delete($file['path']);
            }
            $question->delete();
        }
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
        $file = $request->file('file');
        if($file){
            $fileName = 'ans_'.time().'_'.$file->getClientOriginalName();
            $mainDisk = Storage::disk('google');
            $mainDisk->put($fileName, fopen($file, 'r+'));
            $answer->attachement_path = $fileName;
        }
        $answer->save();

        $asker_id = Question::find($question_id)->asker_id;
        $description = Auth::user()->first_name.' '.Auth::user()->last_name.' posted an answer to your question.';
        $link = url('/answers/'.$question_id);
        Notification::send_notification($asker_id,$description,$link);
        return redirect(url('/answers/'.$question_id));
    }


    public function delete_answer($answer_id, $verified_users_courses)
    {
        $answer = Answer::find($answer_id)->find($answer_id);
        if(Auth::user() && (Auth::user()->role > 0 || Auth::user()->id == $answer->responder_id || $verified_users_courses !== null)){
            if($answer->attachement_path){
                $disk = Storage::disk('google');
                $file = collect($disk->listContents())->where('type', 'file')
                        ->where('extension', pathinfo($answer->attachement_path, PATHINFO_EXTENSION))
                        ->where('filename', pathinfo($answer->attachement_path, PATHINFO_FILENAME))->first();
                $disk->delete($file['path']);
            }
            $answer->delete();
        }
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

    public function list_notes($course_id)
    { 
        $verified_users_courses = null;
        if (Auth::check()){
            $verified_users_courses = VerifiedUsersCourses::where('course_id', $course_id)->where('user_id', Auth::user()->id)->get();
            $role = Auth::user()->role;
        }
        $course = Course::find($course_id);
        if(!$course)
           return 'Ooops! course not found';
        $notes = $course->notes()->where('request_upload', '=', false)->paginate(6);
        return view('notes.notes',compact('notes','role', 'verified_users_courses'));
    }

    public function view_components($category_id)
    {
        $category = ComponentCategory::find($category_id);
        if($category){
            $components = $category->components()->where('accepted',1)->get();
            return view('user.components')->with('components',$components);
        } else
            return "Ooops, category not found";
        
    }

    public function post_component_question(Request $request, $component_id)
    {
        
        $this->validate($request, [
            'question' => 'required'
        ]);
        $question = new ComponentQuestion;
        $question->asker_id = Auth::user()->id;
        $question->question = $request->question;
        $question->component_id = $component_id;
        $file = $request->file('filepath');
        if($file){
            $fileName = 'cq_'.time().'_'.$file->getClientOriginalName();
            $mainDisk = Storage::disk('google');
            $mainDisk->put($fileName, fopen($file, 'r+'));
            $question->attachement_path = $fileName;
        }
        $question->save();
        
        return redirect(url('user/components/'.$component_id));
    }

    public function delete_component_question($component_id, $question_id)
    {
        $question = ComponentQuestion::find($question_id);
        if(Auth::user() && (Auth::user()->role > 0 ||  Auth::user()->id == $question->asker_id)){
            if($question->attachement_path){
                $disk = Storage::disk('google');
                $file = collect($disk->listContents())->where('type', 'file')
                        ->where('extension', pathinfo($question->attachement_path, PATHINFO_EXTENSION))
                        ->where('filename', pathinfo($question->attachement_path, PATHINFO_FILENAME))->first();
                $disk->delete($file['path']);
            }
            $question->delete();
        }
        return redirect(url('user/components/'.$component_id));
    }

    public function delete_component_answer($question_id, $answer_id)
    {
        $answer = ComponentAnswer::find($answer_id);
        if(Auth::user() && (Auth::user()->role > 0 ||  Auth::user()->id == $answer->responder_id)){
            if($answer->attachement_path){
                $disk = Storage::disk('google');
                $file = collect($disk->listContents())->where('type', 'file')
                        ->where('extension', pathinfo($answer->attachement_path, PATHINFO_EXTENSION))
                        ->where('filename', pathinfo($answer->attachement_path, PATHINFO_FILENAME))->first();
                $disk->delete($file['path']);
            }
            $answer->delete();
        }
        return redirect(url('/user/view_component_answers/'.$question_id));
    }

    public function bookmark_component_question($component_id, $question_id)
    {
        $bookmarked_question = BookmarkComponentQuestion::where('user_id', Auth::user()->id)->where('question_id', $question_id)->first();
        if(Auth::user()){

            if($bookmarked_question){
                $bookmarked_question->delete();
                Session::flash('bookmark', 'Question unmarked');
            } else{
                $bookmark = new BookmarkComponentQuestion;
                $bookmark->user_id = Auth::user()->id;
                $bookmark->question_id = $question_id;
                $bookmark->save();
                Session::flash('bookmark', 'Question bookmarked');
            }

        }
        return redirect(url('user/components/'.$component_id));
    }

    public function bookmark_question($id)
    {
        $bookmarked_question = BookmarkQuestion::where('user_id', Auth::user()->id)->where('question_id', $id)->first();
        if(Auth::user()){

            if($bookmarked_question){
                $bookmarked_question->delete();
                Session::flash('bookmark', 'Question unmarked');
            } else{
                $bookmark = new BookmarkQuestion;
                $bookmark->user_id = Auth::user()->id;
                $bookmark->question_id = $id;
                $bookmark->save();
                Session::flash('bookmark', 'Question bookmarked');
            }

        }
        return back();
    }

    public function post_component_answer(Request $request, $question_id)
    {
        
        $this->validate($request, [
            'answer' => 'required'
        ]);
        $answer = new ComponentAnswer;
        $answer->responder_id = Auth::user()->id;
        $answer->answer = $request->answer;
        $answer->component_question_id = $question_id;
        $file = $request->file('file');
        if($file){
            $fileName = 'ca_'.time().'_'.$file->getClientOriginalName();
            $mainDisk = Storage::disk('google');
            $mainDisk->put($fileName, fopen($file, 'r+'));
            $answer->attachement_path = $fileName;
        }
        $answer->save();
        
        return redirect(url('user/view_component_answers/'.$question_id));
    }

    public function view_component_answers($id)
    {
        $question = ComponentQuestion::find($id);
        $answers = ComponentAnswer::where('component_question_id', $id)->paginate(5);
        return view('user.component_question_answers', compact(['question', 'answers']));
    }

    public function component_details($id)
    {
        $component = Component::find($id);
        $questions = ComponentQuestion::where('component_id', $id)->paginate(5);
        return view('user.component_details', compact(['component', 'questions']));
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
        if(Auth::user()->role == 1){
            $component->accepted = 1;
            Session::flash('Added', 'Done, Component is added successfully!');
        }
        else
            Session::flash('Added', 'Done, admins will review your component soon!');
        $component->save();
        return redirect()->back();
    }

    public function download_component_question_attachement($question_id){

        $question =  ComponentQuestion::find($question_id);
        $disk = Storage::disk('google');
        $file = collect($disk->listContents())->where('type', 'file')
                ->where('extension', pathinfo($question->attachement_path, PATHINFO_EXTENSION))
                ->where('filename', pathinfo($question->attachement_path, PATHINFO_FILENAME))->first();

        return response()->redirectTo($disk->url($file['path']));

    }

    public function download_question_attachement($question_id){

        $question =  Question::find($question_id);
        $disk = Storage::disk('google');
        $file = collect($disk->listContents())->where('type', 'file')
                ->where('extension', pathinfo($question->attachement_path, PATHINFO_EXTENSION))
                ->where('filename', pathinfo($question->attachement_path, PATHINFO_FILENAME))->first();

        return response()->redirectTo($disk->url($file['path']));

    }

    public function download_component_answer_attachement($answer_id){

        $answer =  ComponentAnswer::find($answer_id);
        $disk = Storage::disk('google');
        $file = collect($disk->listContents())->where('type', 'file')
                ->where('extension', pathinfo($answer->attachement_path, PATHINFO_EXTENSION))
                ->where('filename', pathinfo($answer->attachement_path, PATHINFO_FILENAME))->first();

        return response()->redirectTo($disk->url($file['path']));

    }

    public function download_answer_attachement($answer_id){

        $answer =  Answer::find($answer_id);
        $disk = Storage::disk('google');
        $file = collect($disk->listContents())->where('type', 'file')
                ->where('extension', pathinfo($answer->attachement_path, PATHINFO_EXTENSION))
                ->where('filename', pathinfo($answer->attachement_path, PATHINFO_FILENAME))->first();

        return response()->redirectTo($disk->url($file['path']));

    }
}
