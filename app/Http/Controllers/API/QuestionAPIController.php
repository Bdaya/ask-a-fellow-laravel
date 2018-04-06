<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers;
use App\Http\Requests;
use App\Question;
use App\Answer;
use App\QuestionVote;
use App\AnswerVote;
use Auth;
use Illuminate\Support\Facades\Storage;

/**
 * Class QuestionAPIController
 * @package App\Http\Controllers\API
 */
class QuestionAPIController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'vote_answer',
            'vote_question'
        ]]);

    }

    /**
     * returns a json object of the header of the question (all of its info)
     */

    /**
     * @param $question_id
     * @return \Illuminate\Http\JsonResponse
     */

    public function view_question_header($question_id)
    {
        $Question = Question::find($question_id);
        if(!$Question){
            return response()->json([
                'error' => [
                    'message' => 'Question requested not found'
                ]

            ], 404);
        }

        $asker = $Question->asker()->first();
        $attachement_url = null;

        if($Question->attachement_path){
            $disk = Storage::disk('google');
            $file = collect($disk->listContents())->where('type', 'file')
                    ->where('extension', pathinfo($Question->attachement_path, PATHINFO_EXTENSION))
                    ->where('filename', pathinfo($Question->attachement_path, PATHINFO_FILENAME))->first();
            $attachement_url = $disk->url($file['path']);
       }

        return response()->json([
            'data' => [
                'question' => $Question['question'],
                'creation' => $Question['created_at'],
                'update' => $Question['updated_at'],
                'votes' => $Question['votes'],
                'attachement_url' => $attachement_url,
                'asker_fname' => $asker['first_name'],
                'asker_lname' => $asker['last_name']
            ]

        ], 200);

    }

    /**
     *  returns a json object of the answers of a certain $question_id order by $order.
     *  $order : votes, latest, oldest.
     **/

    public function view_answers($question_id, $order)
    {

        $question = Question::find($question_id);
        $returnData = array();

        if (!$question) {
            $returnData['status'] = false;
            $returnData['message'] = 'Invalid question id.';
        } else {
            if ($order == 'oldest')
                $answers = $question->answers()->orderBy('created_at', 'asc')->get();
            elseif ($order == 'latest')
                $answers = $question->answers()->orderBy('created_at', 'desc')->get();
            else
                $answers = $question->answers()->orderBy('votes', 'desc')->orderBy('created_at', 'desc')->get();

            $returnData['status'] = true;
            foreach ($answers as $answer) {
               $answer['responder'] = $answer->responder;
               $answer['attachement_url'] = null;
               if($answer->attachement_path){
                    $disk = Storage::disk('google');
                    $file = collect($disk->listContents())->where('type', 'file')
                            ->where('extension', pathinfo($answer->attachement_path, PATHINFO_EXTENSION))
                            ->where('filename', pathinfo($answer->attachement_path, PATHINFO_FILENAME))->first();
                    $answer['attachement_url'] = $disk->url($file['path']);
               }
            }
            $returnData['data'] = $answers;
        }


        return response()->json($returnData);
    }

    public function vote_answer($answer_id, $type)
    {
        $user = Auth::user();

        if($type == 0 && count($user->upvotesOnAnswer($answer_id))){
            $returnData['status'] = false;
            $returnData['message'] = 'Cannot upvote twice';
            return response()->json($returnData);
        }
        if($type == 1 && count($user->downvotesOnAnswer($answer_id))){
            $returnData['status'] = false;
            $returnData['message'] = 'Cannot downvote twice';
            return response()->json($returnData);
        }
        if($type == 0 && count($user->downvotesOnAnswer($answer_id))) {
            $vote = AnswerVote::where('user_id','=', Auth::user()->id)->where('answer_id','=',$answer_id)->first();
            $vote->delete();
        }
        else if($type == 1 && count($user->upvotesOnAnswer($answer_id))) {
            $vote = AnswerVote::where('user_id','=', Auth::user()->id)->where('answer_id','=',$answer_id)->first();
            $vote->delete();
        }
        else
            $user->vote_on_answer($answer_id, $type);

        $answer = Answer::find($answer_id);
        if(Auth::user()->id != $answer->responder_id)
        {
            //send notification
            $responder_id = $answer->responder_id;
            $action = ($type == 0)?' upvoted':' downvoted';
            $description = Auth::user()->first_name.' '.Auth::user()->last_name.$action.' your answer.';
            $link = url('/answers/'.$answer->question_id);
            Notification::send_notification($responder_id,$description,$link);

        }

        $votes = $answer->votes;
        $color = 'black';
        if($votes>0)
            $color = 'green';
        elseif($votes <0)
            $color = 'red';

        $returnData['status'] = true;
        $returnData['answer'] = $answer;
        $returnData['votes'] = $votes;
        $returnData['color'] = $color;

        return response()->json($returnData);

    }

    public function vote_question($question_id, $type)
    {
        $user = Auth::user();

        if($type == 0 && count($user->upvotesOnQuestion($question_id))){
            $returnData['status'] = false;
            $returnData['message'] = 'Cannot upvote twice';
            return response()->json($returnData);
        }
        if($type == 1 && count($user->downvotesOnQuestion($question_id))){
            $returnData['status'] = false;
            $returnData['message'] = 'Cannot downvote twice';
            return response()->json($returnData);
        }
        if($type == 0 && count($user->downvotesOnQuestion($question_id))) {
            $vote = QuestionVote::where('user_id','=', Auth::user()->id)->where('question_id','=',$question_id)->first();
            $vote->delete();
        }
        else if($type == 1 && count($user->upvotesOnQuestion($question_id))) {
            $vote = QuestionVote::where('user_id','=', Auth::user()->id)->where('question_id','=',$question_id)->first();
            $vote->delete();
        }
        else
            $user->vote_on_question($question_id, $type);

        $question = Question::find($question_id);
        if(Auth::user()->id != $question->asker_id)
        {
            //send notification
            $asker_id = $question->asker_id;
            $action = ($type == 0)?' upvoted':' downvoted';
            $description = Auth::user()->first_name.' '.Auth::user()->last_name.$action.' your question.';
            $link = url('/answers/'.$question_id);
            Notification::send_notification($asker_id,$description,$link);

        }

        $votes = Question::find($question_id)->votes;
        $color = 'black';
        if($votes>0)
            $color = 'green';
        elseif($votes <0)
            $color = 'red';

        $returnData['status'] = true;
        $returnData['question'] = $question;
        $returnData['votes'] = $votes;
        $returnData['color'] = $color;

        return response()->json($returnData);

    }

    public function edit_question(Request $request, $question_id)
    {
        $this->validate($request, [
          'question' => 'required'
        ]);

        $question = Question::find($question_id);
        if($question){
            $question->question = $request->question;
            $question->save();
            return response()->json($question, 200);
        }
        else{
            return response()->json(['status' => '404 not found', 'message' => 'Question not found'], 404);
        }
    }

    public function edit_answer(Request $request, $answer_id)
    {
        $this->validate($request, [
          'answer' => 'required'
        ]);

        $answer = Answer::find($answer_id);
        if($answer){
            $answer->answer = $request->answer;
            $answer->save();
            return response()->json($answer, 200);
        }
        else{
            return response()->json(['status' => '404 not found', 'message' => 'Answer not found'], 404);
        }
    }
}
