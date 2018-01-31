<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Review;
use App\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->has('id')) {
            $Stores = Store::find($request->get('id'));
        } else {
            $orderby = 'id';
            $ordertype = 'asc';
            if ($request->has('orderby')) {
                $orderby = $request->get('orderby');
            }
            if ($request->has('ordertype')) {
                $ordertype = $request->get('ordertype');
            }
            $Stores = Store::where('name', 'LIKE', '%' . $request->get('name') . '%')->where('location', 'LIKE', '%' . $request->get('location') . '%')->orderBy($orderby, $ordertype)->paginate(25);
            $Stores->setPath('api/v1/');
        }
        if (!$Stores) {
            $returnData['status'] = false;
            $returnData['message'] = 'There are no stores';
        } else {
            $returnData['status'] = true;
            $returnData['data'] = $Stores;
        }
        return response()->json($returnData);
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
}
