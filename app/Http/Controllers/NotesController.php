<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use App\NoteVote;
use App\Course;
use App\NoteComment;
use Auth;
use Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use App\Http\Requests;
use Response;

class NotesController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  public function request_delete(Request $request)
  {

    $this->validate($request, [
      'comment' => 'required'
    ]);

    $note = Note::find($request->note_id);

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

    redirect()->back()->with(Session::flash('success', $message));

  }

  // view note details
    public function view_note_details($note_id)
    {
        
        $note = Note::find($note_id);
        if(!$note)
            return 'Ooops! note not found';
        //sort answers
        $comments = $note->comments()->paginate(5);

        return view('notes.note_details',compact(['note','comments']));
    }


    // post a comment about a note
    public function post_note_comment(Request $request, $note_id)
    {
        $user = Auth::user();
        if (!$user)
            return 'Ooops! Not authorized';
        $comment = new NoteComment();
        $comment->body = $request->comment;
        $comment->user_id = Auth::user()->id;
        $comment->note_id = $note_id;
        $comment->save();
        return redirect(url('/notes/view_note_details/'.$note_id));
    }

    // delete a previously posted comment about a note
    public function delete_note_comment($note_id, $comment_id)
    {
        $comment = NoteComment::find($comment_id);
        if(Auth::user() && (Auth::user()->role > 0 ||  Auth::user()->id == $comment->user_id))
            $comment->delete();
        
        return redirect(url('/notes/view_note_details/'.$note_id));
    }

    // vote note
    public function vote_note($note_id, $type)
    {
        $user = Auth::user();
        $flag = 0;
        $voted_note = NoteVote::where('user_id', $user->id)->where('note_id', $note_id)->first();

        if (($type == 0 && count($user->upvotesOnNote($note_id))) || ($type == 1 && count($user->downvotesOnNote($note_id))))
        {
            $voted_note->delete();
        }
        elseif (($type == 0 && count($user->downvotesOnNote($note_id))) || ($type == 1 && count($user->upvotesOnNote($note_id))))
        {
            $voted_note->delete(); 
            $user->vote_on_note($note_id, $type); 
            $flag = 1;
        }
        else
        {
            $user->vote_on_note($note_id, $type);
            $flag = 1;
        }

        if($flag == 1){
            $note = Note::find($note_id);
            if($user->id != $note->user_id)
            {
                //send notification
                $user_id = $note->user_id;
                $action = ($type == 0)?' upvoted':' downvoted';
                $description = Auth::user()->first_name.' '.Auth::user()->last_name.$action.' your note.';
                $link = url('/notes/view_note_details/'.$note_id);
                Notification::send_notification($user_id,$description,$link);

            }
        }
        
        return redirect(url('/notes/view_note_details/'.$note_id));  
    }

    public function upload_notes_form(Request $request,$courseID)
    {
        $user = Auth::user();
        if (!$user)
            return 'Ooops! Not authorized';
        return view('notes.uploadNotes');
    }

    public function upload_notes(Request $request, $courseID)
    {
        $user = Auth::user();
        $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'file' => 'required'
        ]);
        $file = $request->file('file');
        $fileName = $request->title.'_'.time().'_'.$file->getClientOriginalName();
        $mainDisk = Storage::disk('google');
        $mainDisk->put($fileName, fopen($file, 'r+'));
        $note = new Note;
        $note->user_id = Auth::user()->id;
        $note->course_id = $courseID;
        $note->title = $request->title;
        $note->path = $fileName;
        $note->description = $request->description;

        if($user->role >= 1)
            $note->request_upload = false;

        Session::flash('success', 'Your request to upload this note is successfull');
        $note->save();

        return back();

    }

    //download the note file
    public function downloadNote($id){
        $note =  Note::find($id);
        $disk = Storage::disk('google');
        $file = collect($disk->listContents())->where('type', 'file')
                ->where('extension', pathinfo($note->path, PATHINFO_EXTENSION))
                ->where('filename', pathinfo($note->path, PATHINFO_FILENAME))->first();

        return response()->redirectTo($disk->url($file['path']));
    }

}
