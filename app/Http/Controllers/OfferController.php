<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Input;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Model\StoreOffer;

class OfferController extends Controller
{
    protected $rules = [
        'user_id' => ['required'],
        'store_id' => ['required'],
        'subject' => ['required', 'min:2'],
        'off' => ['required'],
        'off_type' => ['required'],
        'content' => ['required'],
        //'start_time' => ['required','datetime'],
        //'end_time' => ['required','datetime'],
    ];

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
            'data'  => [],
            'length' => 0
        ];
        $store_id = Input::get("store_id");
        try{
            $offers = Store::find($store_id)->offers()->where("activated", 1)->get();
            $response["length"] = sizeof($offers);
            $response["data"] = $offers;
        }catch (Exception $e){
            $statusCode = Response::HTTP_CONFLICT;
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
        // Retrieve all the users in the database and return them
        $statusCode = Response::HTTP_CONFLICT;
        $response = [];
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user && ($user->id == Input::get("user_id"))) {
            $v = Validator::make($request->all(), $this->rules);
            if ($v->passes()) {
                $data = Input::only('store_id','subject', 'off', 'off_max', 'off_type', 'file_url', 'content', 'start_time', 'end_time');
                $data['user_id'] = $user->id;
                //get the files
                $newStoreOffer = StoreOffer::create($data);
                $response = ['status' => 'success', 'data' => $newStoreOffer];
                $statusCode = Response::HTTP_OK;
                return \Response::json($response, $statusCode);
            }else{
                $ms = $v->messages();
                $statusCode = Response::HTTP_CONFLICT;
                $response = ['status' => "fail", 'message' => $ms];
            }
        }
        return \Response::json($response, $statusCode);
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
        // $request->user() returns an instance of the authenticated user...
        $statusCode = Response::HTTP_OK;
        $response = [
            'data'  => [],
            'length' => 0
        ];
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user) {
            $v = Validator::make($request->all(), $this->rules);
            try{
                $offer = StoreOffer::find($id);
                if ($offer && $v->passes()) {
                    $data = Input::only('store_id', 'subject', 'off', 'off_max', 'off_type', 'file_url', 'content', 'start_time', 'end_time');
                    foreach ($data as $key => $value) {
                        if (null != $value) {
                            $offer->$key = $value;
                        }
                    }
                    $offer->save();
                    $response = [
                        'status' => "success",
                        'message' => "Your offer has been updated. Thank You!"
                    ];
                }else{
                    $ms = $v->messages();
                    $response = [
                        'status' => "success",
                        'message' => $ms
                    ];
                }
            } catch (Exception $e){
                $statusCode = Response::HTTP_CONFLICT;
                $response = [
                    'status' => "fail"
                ];
            }finally{
                return \Response::json($response, $statusCode);
            }
        }
        return \Response::json([
            'status' => "fail",
            'message' => "Your session was expired"
        ], $statusCode);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id){
        //
        // Retrieve all the users in the database and return them
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $storeOffer = StoreOffer::find($id);

        $statusCode = Response::HTTP_NOT_FOUND;
        $status = "fail";
        $ms = "Item not found";
        $response = [
            'status' => $status,
            'message' => $ms
        ];
        if($user && $storeOffer && ($user->id == $storeOffer->user_id)) {
            try{
                $statusCode = Response::HTTP_OK;
                $status = "success";
                $ms = "Your note has just deleted";
                $storeOffer->delete();
            }catch(Exception $e){
                $ms = "500 error. do not delete";
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            }finally{
                $response = [
                    'status' => $status,
                    'message' => $ms
                ];
                return \Response::json($response, $statusCode);
            }
        }
        return \Response::json($response, $statusCode);
    }
}
