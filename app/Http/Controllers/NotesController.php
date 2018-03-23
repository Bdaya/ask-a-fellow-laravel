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

  public function request_delete(Request $request, $note_id)
  {

    $this->validate($request, [
      'comment' => 'required'
    ]);

    $note = Note::find($note_id);

    if(Auth::user() &&  Auth::user()->id == $note->user_id){
      if($note->request_delete == true)
      return 'you already requested to delete this note';

      $note->request_delete = true;
      $note->comment_on_delete = $request -> $delete_comment;
      $note->save();

      Session::flash('updated', 'Your request to delete this note is now handled');
      redirect(url('/note/'.$note->id));
    }
    else
    return 'Not allowed to delete this note';

  }

  // view note details
    public function view_note_details($note_id)
    {
        
        $note = Note::find($note_id);
        if(!$note)
            return 'Ooops! note not found';
        //sort answers
        $comments = $note->comments()->get();

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

        if ($type == 0 && count($user->upvotesOnNote($note_id)))
            return '<span style="color:black">Cannot upvote twice</span>';

        if ($type == 1 && count($user->downvotesOnNote($note_id)))
            return '<span style="color:black">Cannot downvote twice</span>';

        if ($type == 0 && count($user->downvotesOnNote($note_id))) {
            $vote = NoteVote::where('user_id', '=', Auth::user()->id)->where('note_id', '=', $note_id)->first();
            $vote->delete();
        } else if ($type == 1 && count($user->upvotesOnNote($note_id))) {
            $vote = NoteVote::where('user_id', '=', Auth::user()->id)->where('note_id', '=', $note_id)->first();
            $vote->delete();
        } else{ 
            $user->vote_on_note($note_id, $type); 
        }

        $note = Note::find($note_id);
        if(Auth::user()->id != $note->user_id)
        {
            //send notification
            $user_id = $note->user_id;
            $action = ($type == 0)?' upvoted':' downvoted';
            $description = Auth::user()->first_name.' '.Auth::user()->last_name.$action.' your note.';
            $link = url('/notes/view_note_details/'.$note_id);
            Notification::send_notification($user_id,$description,$link);

        }


        $votes = $note->votes;
        $color = 'black';
        if($votes>0)
            $color = 'green';
        elseif($votes <0)
            $color = 'red';
        return '<span style="color:'.$color.'"">'.$votes.'</span>';     
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
            'title' => 'alpha|required',
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
        $note->request_upload = true;
        $note->comment_on_delete="";

        Session::flash('success', 'Your request to upload this note is successfull');
        $note->save();

        return back();

    }

    //download the note file
    public function downloadNote($id) {
        $note =  Note::find($id);
        $disk = Storage::disk('google');
        $file = collect($disk->listContents())->where('type', 'file')
                ->where('extension', pathinfo($note->path, PATHINFO_EXTENSION))
                ->where('filename', pathinfo($note->path, PATHINFO_FILENAME))->first();

        return Response::make(file_get_contents($disk->url($file['path'])), 200, [
            'Content-Disposition' => 'attachment; filename="'.$note->path.'"'
        ]);
    }

}
