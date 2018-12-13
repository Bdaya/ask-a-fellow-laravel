<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Course;
use App\Major;
use App\Notification;
use App\Question;
use App\QuestionVote;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'vote_question', 
            'post_question',
            'home',
            'post_answer', 
            'getSubscribedCourses'
        ]]);
    }

    public function documentation(){
        return '
        <!DOCTYPE html>
        <head>
        <h1 style="color:green">API Documentation</h1>
        </head>
        <body>
        <h3>1. /register (Post Request)</h3>
        <p>Sends confirmation mail and creates user. Returns welcome message and status code 200</p>
        </br>
        <h3>2. /register/verify/{token} (Get Request)</h3>
        <p>Verifies user with confirmation code from email. Returns message of verification and status code 200</p>
        </br>
        <h3>3. /login (Post Request)</h3>
        <p>Login for user. Params: "email" and "password". Returns user token upon successful and error upon failure</p>
        </br>
        <h3>4. /logout (Post Request)</h3>
        <p>Logout for user. Header: "x-access-token". Returns message and status code 200</p>
        </body>
        ';
    }

    /**
     * Return majors and and semesters 
     */
    public function browse()
    {   
        $majors = Major::all();
        $semesters = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        return ['majors' => $majors, 'semesters' => $semesters];
    }

    public function getCourses($major, $semester)
    {
        $major = Major::find($major);
        $courses = $major->courses()->where('semester', '=', $semester)->get();
        return ['courses' => $courses];
    }

    public function getSubscribedCourses()
    {
        $user = Auth::user();
        $courses = $user->subscribed_courses();
        return ['courses' => $courses];
    }

    public function list_questions($course_id, $order = null)
    {
        $course = Course::find($course_id);
        //sort questions
        if (!$course)
            return ['error' => 'course not found'];
        $new_questions = array();
        if ($order == 'votes') 
            $questions = $course->questions()->orderBy('votes', 'desc')->paginate(10);
        elseif ($order == 'oldest')
            $questions = $course->questions()->oldest()->paginate(10);
        elseif ($order = 'answers'){
            $questions = $course->questions()->withCount('answers')->orderBy('answers_count', 'desc')->paginate(10); 
        }
        else
            $questions = $course->questions()->orderBy('created_at', 'desc')->paginate(10);
        foreach ($questions as $question) {
            $question['file_url'] = null;
            $question['asker'] = $question->asker()->get();
            $question['count_answers'] = $question->answers()->get()->count();
            if($question->attachement_path){
                $disk = Storage::disk('google');
                $file = collect($disk->listContents())->where('type', 'file')
                        ->where('extension', pathinfo($question->attachement_path, PATHINFO_EXTENSION))
                        ->where('filename', pathinfo($question->attachement_path, PATHINFO_FILENAME))->first();
                $question['file_url'] = $disk->url($file['path']);
           }
        }
        $questions->setPath('api/v1/');
        $count_questions = count($course->questions()->get());

        return ['questions' => $questions, 'count_questions' => $count_questions];
    }

    public function post_question(Request $request, $course_id)
    {
        $this->validate($request, [
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
        return ['state' => '200 ok', 'error' => false,'data'=>$question];
    }

    public function post_answer(Request $request,$question_id)
    {
        $this->validate($request, [
                'answer' => 'required|min:5',
        ]);
        $answer = new Answer();
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
        return ['state' => '200 ok', 'error' => false,'data'=>$answer];
    }

    public function home()
    {
        $user  = Auth::user();
        $questions = $user->home_questions();
        $count_questions = count($questions->get());
        $questions = $questions->orderBy('created_at','desc')->paginate(10);
        foreach ($questions as $question) {
           
            $question['asker'] = $question->asker()->get();
            $question['count_answers'] = $question->answers()->get()->count();
        }
        $questions->setPath('api/v1/');
       return $questions;
    }
}
