<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Note;
use App\Course;
use App\NoteComment;
use App\NoteVote;
use App\Notification;
use Illuminate\Support\Facades\Storage;


class NotesAPIController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'post_comment',
            'vote_note',
            'request_delete'
        ]]);
    }

	public function index($course_id)
    {

    	$course = Course::find($course_id);
        if (!$course) {
            return response()->json(['status' => '404 not found', 'message' => 'Course not found'], 404);
        }
        $notes = $course->notes()->where('request_upload', false)->paginate(6); 
        $notes->setPath('api/v1/');
        if (!$notes) {
            $returnData['status'] = false;
            $returnData['message'] = 'There are no notes for this course';
        } else{
        	$returnData['status'] = true;
            $returnData['data'] = $notes;
        }
        return response()->json($returnData);
    }

    public function show($note_id)
    {
        $note = Note::find($note_id);
        if (!$note) {
            return response()->json(['status' => '404 not found', 'message' => 'note not found']);
        }
        $comments = $note->comments()->paginate(5);
        $returnedData = [];
        $returnedData['status'] = '200 ok';
        $returnedData['error'] = null;
        $returnedData['data'] = [];
        $returnedData['data']['note'] = $note;
        $returnedData['data']['note']['comments'] = $comments;

        return response()->json($returnedData, 200);
    }

    public function post_comment(Request $request, $note_id)
    {
        $this->validate($request, [
            'comment' => 'required'
        ]);

        $comment = new NoteComment();
        $comment->body = $request->comment;
        $comment->user_id = Auth::user()->id;
        $comment->note_id = $note_id;
        $comment->save();
        
        return ['state' => '200 ok', 'error' => false,'data'=>$comment];
    }

    public function vote_note($note_id, $type)
    {
        $user = Auth::user();
        $flag = 0;
        $voted_note = NoteVote::where('user_id', $user->id)->where('note_id', $note_id)->first();

        if (($type == 0 && count($user->upvotesOnNote($note_id))) || ($type == 1 && count($user->downvotesOnNote($note_id))))
        {
            $voted_note->delete();
            $returnData['message'] = 'Vote removed';
        }
        elseif (($type == 0 && count($user->downvotesOnNote($note_id))) || ($type == 1 && count($user->upvotesOnNote($note_id))))
        {
            $voted_note->delete(); 
            $user->vote_on_note($note_id, $type); 
            $flag = 1;
            $returnData['message'] = 'Vote reversed';
        }
        else
        {
            $user->vote_on_note($note_id, $type);
            $flag = 1;
            $returnData['message'] = 'Vote added';
        }

        $note = Note::find($note_id);

        if($flag == 1){
            if($user->id != $note->user_id)
            {
                //send notification
                $user_id = $note->user_id;
                $action = ($type == 0)?' upvoted':' downvoted';
                $description = Auth::user()->first_name.' '.Auth::user()->last_name.$action.' your note.';
                $link = url('/note_details/'.$note_id);
                Notification::send_notification($user_id,$description,$link);

            }
        }

        $votes = $note->votes;
        $color = 'black';
        if($votes>0)
            $color = 'green';
        elseif($votes <0)
            $color = 'red';

        $returnData['status'] = true;
        $returnData['note'] = $note;
        $returnData['votes'] = $votes;
        $returnData['color'] = $color;

        return response()->json($returnData);

    }

    public function request_delete(Request $request, $note_id)
    {

        $this->validate($request, [
          'comment' => 'required'
        ]);

        $note = Note::find($note_id);

        if(Auth::user() &&  Auth::user()->id == $note->user_id){
            if($note->request_delete == true)
                $message = 'You already requested to delete this note';
            else{
                $note->request_delete = true;
                $note->comment_on_delete = $request->comment;
                $note->save();
                $message = 'Your request to delete this note is now handled';
            }
        }
        else
            $message = 'You are not allowed to delete this note';

        return response()->json(['message' => $message]);

    }

    public function edit_note_comment(Request $request, $comment_id)
    {
        $this->validate($request, [
          'comment' => 'required'
        ]);

        $comment = NoteComment::find($comment_id);
        if($comment){
            $comment->body = $request->comment;
            $comment->save();
            return response()->json($comment, 200);
        }
        else{
            return response()->json(['status' => '404 not found', 'message' => 'note comment not found'], 404);
        }
    }

    public function downloadNote($id){
        $note =  Note::find($id);
        $disk = Storage::disk('google');
        $file = collect($disk->listContents())->where('type', 'file')
                ->where('extension', pathinfo($note->path, PATHINFO_EXTENSION))
                ->where('filename', pathinfo($note->path, PATHINFO_FILENAME))->first();
        return response()->json(['message' => $disk->url($file['path'])]);

    }

}