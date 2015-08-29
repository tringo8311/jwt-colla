<?php

namespace App\Http\Controllers;

use App\Model\UserReservationStore;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Validator;
use JWTAuth;

class ProfileReservationController extends Controller
{
    protected $rules = [
        'store_id' => ['required', 'min:2'],
        'prefer' => ['min:2', 'max:128'],
        'datetime' => ['required'],
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
        $statusCode = Response::HTTP_OK;
        $response = [
            'data'  => [],
            'length' => 0
        ];
        // Retrieve all the users in the database and return them
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        //$keyword = Input::get('keyword');
        try{
            if($user){
                $reservation = UserReservationStore::where('user_id', $user->id)->orderBy('created_at', 'desc');
                $reservations = $reservation->get();
                $data = array();
                foreach ($reservations as $reservation) {
                    $data[] = $reservation->toArray();
                }
                $response["length"] = sizeof($data);
                $response["data"] = $data;
            }
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
        // Retrieve all the users in the database and return them
        $statusCode = Response::HTTP_OK;
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if($user) {
            $v = Validator::make($request->all(), $this->rules);
            if ($v->passes()) {
                $input = Input::only('store_id', 'prefer', 'content');
                $input['datetime'] = Input::get('date');
                    //. " " . Input::only('time');
                $input['user_id'] = $user->id;
                $newReservation = UserReservationStore::create($input);
                return \Response::json(['status' => 'success', 'data' => $newReservation], $statusCode);
            }else{
                $ms = $v->messages();
                return \Response::json(['status' => "fail", 'messages' => $ms], $statusCode);
            }
        }
        return \Response::json([
            'status' => "fail",
            'message' => "Your session was expired"
        ], $statusCode);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $reservation = UserReservationStore::find($id);
        $ms = "Item not found";
        $statusCode = 404;
        $status = "fail";
        if($user && $reservation && ($user->id == $reservation->user_id)) {
            return \Response::json(['status' => $status, 'message' => $ms, 'data' => $reservation], $statusCode);
        }else{
            return \Response::json(['status' => $status,'message' => $ms], $statusCode);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $userId, $id)
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $response = [
            'status' => "fail",
            'message' => "Your session was expired"];
        $statusCode = Response::HTTP_CONFLICT;
        try{
            if($user){
                $v = Validator::make($request->all(), $this->rules);
                $reservation = UserReservationStore::find($id);
                if ($reservation && $v->passes()) {
                    $data = Input::only('store_id', 'prefer', 'content');
                    $input['datetime'] = Input:: nbget('date');
                    $input['user_id'] = $user->id;
                    foreach ($data as $key => $value) {
                        if (null != $value) {
                            $reservation->$key = $value;
                        }
                    }
                    $reservation->save();

                    $statusCode = Response::HTTP_OK;
                    $response['status'] = "success";
                    $response['message'] = "Your reservation has been updated. Thank You!";
                    $response["data"] = $reservation;
                }else{
                    $response['message'] = $v->messages();
                }
            }
        }catch (Exception $e){
            $statusCode = Response::HTTP_CONFLICT;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }

    /**
     * Remove the specified resource from storage.
     *     * @param  int  $id
     * @return Response
     */
    public function destroy($userId, $id)
    {
        //
        // Retrieve all the users in the database and return them
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $reservation = UserReservationStore::find($id);
        $ms = "Item not found";
        $statusCode = 404;
        $status = "fail";
        if($user && $reservation && ($user->id == $reservation->user_id)) {
            try{
                $statusCode = 200;
                $status = "success";
                $ms = "Your note has just deleted";
                $reservation->delete();
            }catch(Exception $e){
                $ms = "500 error. do not delete";
                $statusCode = 500;
            }finally{
                return \Response::json(['status' => $status,'message' => $ms, 'statusCode' => $statusCode]);
            }
        }else{
            return \Response::json(['status' => $status,'message' => $ms, 'statusCode' => $statusCode]);
        }
    }
}
