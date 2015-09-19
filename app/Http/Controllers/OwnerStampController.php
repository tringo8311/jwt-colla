<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;

use App\Model\UserStampStore;
use App\Http\Requests;
use Validator;
use JWTAuth;

class OwnerStampController extends Controller
{
    protected $rules = [
        'user_id' => ['required'],
        'store_id' => ['required'],
        'paid' => ['min:1', 'max:9'],
        'datetime' => ['required'],
    ];
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
        $followerId = Input::get('follower_id');
        $storeId = Input::get('store_id');
        //$statusArr = Input::get('status') ? Input::get('status') : null;
        // TODO: validation Request params
        try{
            if($user){
                $stamp = UserStampStore::where('store_id', $storeId)->where('user_id', $followerId)->orderBy('created_at', 'desc');
                //if(!empty($statusArr)){
                    //$stamp->whereIn('status', $statusArr);
                //}
                //if($keyword && $keyword != ""){
                    //$stamp->where('content', 'like' ,"%$keyword%");
                    //->orWhere('prefer', 'like' ,"%$keyword%");
                //}
                $stamps = $stamp->get();
                $data = array();
                foreach ($stamps as $stamp) {
                    $data[] = $stamp->toArray();
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
                $input = Input::only('user_id', 'store_id', 'datetime', 'paid', 'content');
                $input['created_id'] = $user->id;
                $newStamp = UserStampStore::create($input);
                return \Response::json(['status' => 'success', 'data' => $newStamp], $statusCode);
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
                $stamp = UserStampStore::find($id);
                if ($stamp && $v->passes()) {
                    $data = Input::only('datetime', 'paid', 'content');
                    foreach ($data as $key => $value) {
                        if (null != $value) {
                            $stamp->$key = $value;
                        }
                    }
                    $stamp->save();

                    $statusCode = Response::HTTP_OK;
                    $response['status'] = "success";
                    $response['message'] = "Your stamp has been updated. Thank You!";
                    $response["data"] = $stamp;
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
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
