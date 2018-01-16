<?php

namespace App\Http\Controllers\API;

use App\Review;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class StoresAPIController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['only' => [
            'addReview'
        ]]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'review' => 'required',
            'rate' => 'required'
        ]);
    }

    /**
     * Get a list of the available stores
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $stores = Store::paginate(25);
        return response()->json($stores, 200);
    }

    /**
     * Get the details of a specific store
     *
     * @param $store_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($store_id)
    {
        $store = Store::find($store_id);
        if (!$store) {
            return response()->json(['status' => '404 not found', 'message' => 'store not found']);
        }
        $reviews = $store->reviews()->get();
        $returnedData = [];
        $returnedData['status'] = '200 ok';
        $returnedData['error'] = null;
        $returnedData['data'] = [];
        $returnedData['data']['store'] = $store;
        $returnedData['data']['store']['reviews'] = $reviews;

        return response()->json($returnedData, 200);
    }

    /**
     * Add a review to a specific store
     *
     * @param $store_id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addReview($store_id, Request $request)
    {
        $store = Store::find($store_id);
        $data = $request->all();
        if (!$store) {
            return response()->json(['status' => '404 not found', 'message' => 'store not found'], 404);
        }
        $validator = $this->validator($request->all());

        if ($validator->fails())
            return response()->json($validator->errors(), 302);

        $review = new Review($request->all());
        $review['store_id'] = $store_id;
        $review['user_id'] = Auth::user()->id;

        $store['rate_count'] += 1;
        $store['rate'] = ($data['rate'] + $store['rate']) / ($store['rate_count']);

        $store->save();
        $review->save();
        return response()->json($review, 200);
    }

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
