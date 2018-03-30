<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Note;
use App\Course;
use App\NoteComment;

class NotesAPIController extends Controller
{
	public function index($course_id)
    {

    	$course = Course::find($course_id);
        if (!$course) {
            return response()->json(['status' => '404 not found', 'message' => 'Course not found'], 404);
        }
        $notes = $course->notes()->where('request_upload', false)->paginate(10); 
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
        $comments = $note->comments()->get();
        $returnedData = [];
        $returnedData['status'] = '200 ok';
        $returnedData['error'] = null;
        $returnedData['data'] = [];
        $returnedData['data']['note'] = $note;
        $returnedData['data']['note']['comments'] = $comments;

        return response()->json($returnedData, 200);
    }

}