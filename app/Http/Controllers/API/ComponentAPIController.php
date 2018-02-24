<?php

namespace App\Http\Controllers\API;

use App\Component;
use App\ComponentQuestion;
use App\ComponentAnswer;
use App\Notification;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComponentAPIController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'component_ask',
            'post_answer'
        ]]);
    }

    /**
     * Get a list of the components
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->has('id')) {
            $Components = Component::join('component_categories', 'components.category_id', '=', 'component_categories.id')->join('users', 'components.creator_id', '=', 'users.id')->select('components.*', 'component_categories.name as category_name', 'users.first_name as creator_first_name', 'users.last_name as creator_last_name')->find($request->get('id'));
        } else {
            $orderby = 'id';
            $ordertype = 'asc';
            if ($request->has('orderby'))
                $orderby = $request->get('orderby');
            if ($request->has('ordertype'))
                $ordertype = $request->get('ordertype');
            $Components = Component::join('component_categories', 'components.category_id', '=', 'component_categories.id')->join('users', 'components.creator_id', '=', 'users.id')->select('components.*', 'component_categories.id as category_id', 'component_categories.name as category_name', 'users.id as creator_id', 'users.first_name as creator_first_name', 'users.last_name as creator_last_name')->where('title', 'LIKE', '%' . $request->get('title') . '%')->where('component_categories.name', 'LIKE', '%' . $request->get('category') . '%')->where('users.first_name', 'LIKE', '%' . $request->get('creator_first_name') . '%')->where('users.last_name', 'LIKE', '%' . $request->get('creator_last_name') . '%')->orderBy($orderby, $ordertype)->paginate(25);
            $Components->setPath('api/v1/');
        }
        if (!$Components) {

            $returnData['status'] = '404 not found';
            $returnData['message'] = 'There are no components';

        } else {

            $returnData['status'] = '200 ok';
            $returnData['data'] = $Components;

        }
        return response()->json($returnData);
    }

    /**
     * Get the details of a specific component
     *
     * @param $component_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($component_id)
    {
        $component = Component::find($component_id);
        if (!$component) {
            return response()->json(['status' => '404 not found', 'message' => 'Component not found']);
        }
        $component_questions = $component->questions()->get();
        $returnedData = [];
        $returnedData['status'] = '200 ok';
        $returnedData['error'] = null;
        $returnedData['data'] = [];
        $returnedData['data']['component'] = $component;
        $returnedData['data']['component']['questions'] = $component_questions;

        return response()->json($returnedData, 200);
    }

    /**
     * Ask a question about a component
     *
     * @param Request $request
     * @param $component_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function component_ask(Request $request, $component_id)
    {
        $this->validate($request, [
            'question' => 'required'
        ]);
        $question = new ComponentQuestion;
        $question->asker_id = Auth::user()->id;
        $question->question = $request->question;
        $question->component_id = $component_id;
        $question->save();
        
        return ['state' => '200 ok', 'error' => false,'data'=>$question];
    }
    
    /**
    *   Post an answer to a component question and notifies
    *   the asker
    *
    *   @param Request $request
    *   @param $question_id
    *   @return status
    */
    public function post_answer(Request $request,$question_id)
    {
        $this->validate($request, [
                'answer' => 'required|min:5',
        ]);
        
        $answer = new ComponentAnswer();
        $answer->answer = $request->answer;
        $answer->responder_id = Auth::user()->id;
        $answer->question_id = $question_id;
        $answer->save();
        
        $asker_id = ComponentQuestion::find($question_id)->asker_id;
        $component_id = ComponentQuestion::find($question_id)->component_id;
        $item_title = Component::find($component_id)->title;
            
        $description = Auth::user()->first_name.' '.Auth::user()->last_name.' posted an answer to your question about the item '.$item_title;
        $link = url('/component/answers/'.$question_id);
        Notification::send_notification($asker_id,$description,$link);
        
        
        return ['state' => '200 ok', 'error' => false,'data'=>$answer];
    }
}
