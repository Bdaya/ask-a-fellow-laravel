<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\ComponentQuestion;
use App\Http\Requests;
use App\Component;
use App\User;

class ComponentAPIController extends Controller
{

	public function view_components()
    {
    	$Components = Component::paginate(10);

        if(!$Components){

        	$returnData['status'] = false;
            $returnData['message'] = 'There are no components';

        } else{

        	$returnData['status'] = true;

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

}