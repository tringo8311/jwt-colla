<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\HttpResponse;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Model\Store;

class OwnerController extends Controller
{
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        //$this->middleware('jwt.auth', ['except' => ['update']]);
        $this->middleware('jwt.auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $statusCode = 200;
        $response = [
            'data'  => []
        ];
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $store_id = Input::get('store_id');;
        try{
            $followerSize = Store::find($store_id)->users()->where("user_id", "!=", $user->id)->count();
            $rateAverage = Store::find($store_id)->feedbacks()->avg('rate');
            $offerSize = Store::find($store_id)->offers()->where("activated", 1)->count();
            $response['data'] = [
                'followerSize' => $followerSize,
                'rateAverage' => $rateAverage,
                'offerSize' => $offerSize,
                'report' => ''
            ];
        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
