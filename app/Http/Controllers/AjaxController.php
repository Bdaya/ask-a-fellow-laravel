<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

use App\Http\Requests;
use App\Major;
use App\Answer;
use Auth;
use App\AnswerVote;
use App\Question;
use App\QuestionVote;
use App\Notification;
use App\QuestionReport;
use App\AnswerReport;
use App\User;
use App\NoteComment;
use App\ComponentQuestion;
use App\ComponentAnswer;

class AjaxController extends Controller
{
    public function __construct(){
        $this->middleware('ajax');
        $this->middleware('auth', ['only' => [
            'vote_answer',
            'vote_question',
            'view_notifications_partial',
            'mark_notification',
            'send_report_answer',
            'send_report_question',
            'edit_note_comment',
            'edit_component_question',
            'edit_question',
            'edit_component_answer',
            'edit_answer'
        ]]);
    }

    public function edit_note_comment(Request $request)
    {
        $this->validate($request, [
          'comment' => 'required'
        ]);

        $user = Auth::user();
        if (!$user)
            redirect()->back()->with(Session::flash('success', 'Ooops! Not authorized'));
        else{
            $comment = NoteComment::find($request->comment_id);
            if($user->id == $comment->user_id){
                $comment->body = $request->comment;
                $comment->save();
            }
            redirect()->back();
        }
    }

    public function edit_component_question(Request $request)
    {
        $this->validate($request, [
          'question' => 'required'
        ]);

        $user = Auth::user();
        if (!$user)
            redirect()->back()->with(Session::flash('success', 'Ooops! Not authorized'));
        else{
            $question = ComponentQuestion::find($request->question_id);
            if($user->id == $question->asker_id){
                $question->question = $request->question;
                $question->save();
            }
            redirect()->back();
        }
    }

    public function edit_question(Request $request)
    {
        $this->validate($request, [
          'question' => 'required'
        ]);

        $user = Auth::user();
        if (!$user)
            redirect()->back()->with(Session::flash('success', 'Ooops! Not authorized'));
        else{
            $question = Question::find($request->question_id);
            if($user->id == $question->asker_id){
                $question->question = $request->question;
                $question->save();
            }
            redirect()->back();
        }
    }

    public function edit_component_answer(Request $request)
    {
        $this->validate($request, [
          'answer' => 'required'
        ]);

        $user = Auth::user();
        if (!$user)
            redirect()->back()->with(Session::flash('success', 'Ooops! Not authorized'));
        else{
            $answer = ComponentAnswer::find($request->answer_id);
            if($user->id == $answer->responder_id){
                $answer->answer = $request->answer;
                $answer->save();
            }
            redirect()->back();
        }
    }

    public function edit_answer(Request $request)
    {
        $this->validate($request, [
          'answer' => 'required'
        ]);

        $user = Auth::user();
        if (!$user)
            redirect()->back()->with(Session::flash('success', 'Ooops! Not authorized'));
        else{
            $answer = Answer::find($request->answer_id);
            if($user->id == $answer->responder_id){
                $answer->answer = $request->answer;
                $answer->save();
            }
            redirect()->back();
        }
    }

    public function getCourses($major, $semester)
    {
        $major = Major::find($major);
        $courses = $major->courses()->where('semester','=',$semester)->get();
        return view('browse.courses_listing',compact(['courses','major','semester']));
    }

    public function vote_answer($answer_id, $type)
    {
        $user = Auth::user();
        $flag = 0;
        $voted_answer = AnswerVote::where('user_id', $user->id)->where('answer_id', $answer_id)->first();

        if (($type == 0 && count($user->upvotesOnAnswer($answer_id))) || ($type == 1 && count($user->downvotesOnAnswer($answer_id))))
        {
            $voted_answer->delete();
        }
        elseif (($type == 0 && count($user->downvotesOnAnswer($answer_id))) || ($type == 1 && count($user->upvotesOnAnswer($answer_id))))
        {
            $voted_answer->delete(); 
            $user->vote_on_answer($answer_id, $type); 
            $flag = 1;
        }
        else
        {
            $user->vote_on_answer($answer_id, $type);
            $flag = 1;
        }

        if($flag == 1){
            $answer = Answer::find($answer_id);
            if($user->id != $answer->responder_id)
            {
                //send notification
                $responder_id = $answer->responder_id;
                $action = ($type == 0)?' upvoted':' downvoted';
                $description = Auth::user()->first_name.' '.Auth::user()->last_name.$action.' your answer.';
                $link = url('/answers/'.$answer->question_id);
                Notification::send_notification($responder_id,$description,$link);

            }
        }
        
        return back();
    }

    public function vote_question($question_id, $type)
    {
        $user = Auth::user();
        $flag = 0;
        $voted_question = QuestionVote::where('user_id', $user->id)->where('question_id', $question_id)->first();

        if (($type == 0 && count($user->upvotesOnQuestion($question_id))) || ($type == 1 && count($user->downvotesOnQuestion($question_id))))
        {
            $voted_question->delete();
        }
        elseif (($type == 0 && count($user->downvotesOnQuestion($question_id))) || ($type == 1 && count($user->upvotesOnQuestion($question_id))))
        {
            $voted_question->delete(); 
            $user->vote_on_question($question_id, $type); 
            $flag = 1;
        }
        else
        {
            $user->vote_on_question($question_id, $type);
            $flag = 1;
        }

        if($flag == 1){
            $question = Question::find($question_id);
            if($user->id != $question->asker_id)
            {
                //send notification
                $asker_id = $question->asker_id;
                $action = ($type == 0)?' upvoted':' downvoted';
                $description = Auth::user()->first_name.' '.Auth::user()->last_name.$action.' your question.';
                $link = url('/answers/'.$question_id);
                Notification::send_notification($asker_id,$description,$link);

            }
        }
        
        return back();
    }

    public function view_notifications_partial()
    {
        $user = Auth::user();
        $unread = count($user->new_notifications);
        $notifications = $user->notifications()->take(max($unread,8))->orderBy('created_at','desc')->get();
        foreach($notifications as $notification) {
            $notification->seen = 1;
            $notification->save();
        }

        return view('user.partial_notifications',compact(['notifications','unread']));

    }

    public function mark_notification($notification_id, $read)
    {
        $notification = Notification::find($notification_id);
        if($read == 0) {
            $notification->seen = 0;
            $notification->save();
            return '<a href="#" class="mark_as_read" value='.$notification_id.'">Mark as read</a>';
        }
        else if($read == 1) {
            $notification->seen = 1;
            $notification->save();
            return '<a href="#" class="mark_as_unread" value='.$notification_id.'">Mark as unread</a>';

        }

    }

    public function send_report_question(Request $request)
    {
        $reason = $request->reason;
        $other = $request->other;
        if($reason == 'Other')
            $reason = $other;
        $question_id = $request->question_id;
        if(!$reason)
            $reason = 'Unknown';

        $report = new QuestionReport;
        $report->report = $reason;
        $report->user_id = Auth::user()->id;
        $report->question_id = $request->question_id;
        $report->link = url('/answers/'.$question_id);
        $report->save();
        $admins = User::where('role','>',0)->get(['id']);
        $description = Auth::user()->first_name.' '.Auth::user()->last_name.' reported a question.';
        $link = $report->link;
        foreach($admins as $admin)
            Notification::send_notification($admin->id,$description,$link);
        return "Report submitted successfully";

    }

    public function send_report_answer(Request $request)
    {
        $reason = $request->reason;
        $other = $request->other;
        if($reason == 'Other')
            $reason = $other;

        if(!$reason)
            $reason = 'Unknown';

        $answer_id = $request->answer_id;
        $report = new AnswerReport;
        $report->report = $reason;
        $report->user_id = Auth::user()->id;
        $report->answer_id = $request->answer_id;
        $report->link = url('/answers/'.Answer::find($answer_id)->question_id);
        $report->save();
        $admins = User::where('role','>',0)->get(['id']);
        $description = Auth::user()->first_name.' '.Auth::user()->last_name.' reported an answer.';
        $link = $report->link;
        foreach($admins as $admin)
            Notification::send_notification($admin->id,$description,$link);
        return "Report submitted successfully";

    }

}
