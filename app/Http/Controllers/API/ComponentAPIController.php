<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\ComponentQuestion;
use App\ComponentCategory;
use App\Http\Requests;
use App\Component;
use App\User;

class ComponentAPIController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'component_ask'
        ]]);
    }

    // Get a list of the available components
	public function view_components()
    {
    	$Components = Component::paginate(25);

        if(!$Components){

        	$returnData['status'] = '404 not found';
            $returnData['message'] = 'There are no components';

        } else{

        	$returnData['status'] = '200 ok';

            foreach ($Components as $Component) {

            	$Component['category'] = $Component->category()->name;
            	$Component['creator_first_name'] = $Component->creator()->first_name;
            	$Component['creator_last_name'] = $Component->creator()->last_name;
               
            }

            $Components->setPath('api/v1/');
            $returnData['data'] = $Components;

        }

        return response()->json($returnData);
    }

    // Ask a question about a component
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
    

    // search by title
    public function search_by_title($title){
        $Components = Component::where('title', $title)->orderBy('title', 'asc')->get();
        if(!$Components || count($Components) == 0){
            $returnData['status'] = false;
            $returnData['message'] = 'There are no components with that title';
        }else{
            $returnData['status'] = true;
            $returnData['data'] = $Components;
        }
        return response()->json($returnData);
    }

    // search by price
    public function search_by_price($price){
        $Components = Component::where('price', $price)->orderBy('price', 'asc')->get();
        if(!$Components || count($Components) == 0){
            $returnData['status'] = false;
            $returnData['message'] = 'There are no components with that price';
        }else{
            $returnData['status'] = true;
            $returnData['data'] = $Components;
        }
        return response()->json($returnData);
    }

    // search by category
    public function search_by_category($category){
        $categoryid = ComponentCategory::where('name', $category)->first(); // get the category id from the component_categories table
        if(!$categoryid){
            $returnData['status'] = false;
            $returnData['message'] = 'Component category not found';
            return response()->json($returnData);
        }
        $Components = Component::where('category_id', $categoryid->id)->orderBy('title', 'asc')->get(); // get all components under this category
        
        if(!$Components || count($Components) == 0){
            $returnData['status'] = false;
            $returnData['message'] = 'There are no components under that category';
        }else{
            $returnData['status'] = true;
            $returnData['data'] = $Components;
        }
        return response()->json($returnData);
    }

}
