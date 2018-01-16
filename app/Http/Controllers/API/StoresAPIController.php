<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Store;


class StoresAPIController extends Controller
{
    // Search and sort with all attributes. Empty attributes are set '_'. Default order is by rate descendingly
    public function search_and_sort_stores($id, $name, $location, $orderby, $ordertype){
        if ($id != '_'){
            $Stores = Store::find($id);
        }else{
            if ($name == '_')
                $name = null;
            if ($location == '_')
                $location = null;
            if ($orderby == '_')
                $orderby = 'rate';
            if ($ordertype == '_')
                $ordertype = 'desc';
            $Stores = Store::where('name', 'LIKE', '%'.$name.'%')->where('location', 'LIKE', '%'.$location.'%')->orderBy($orderby, $ordertype)->paginate(10);
            $Stores->setPath('api/v1/');
        }
        if(!$Stores){
        	$returnData['status'] = false;
            $returnData['message'] = 'There are no stores';
        } else{
        	$returnData['status'] = true;
            $returnData['data'] = $Stores;
        }
        return response()->json($returnData);
    }
}
