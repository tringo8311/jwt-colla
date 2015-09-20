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
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        //$this->middleware('jwt.auth', ['except' => ['update']]);
        $this->middleware('jwt.auth');
        \DB::enableQueryLog();
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
        $followerId = Input::get('follower_id');
        $storeId = Input::get('store_id');
        $period = Input::get('period') ? Input::get('period') : null;
        // TODO: validation Request params
        try{
            if($user){
                $stamp = UserStampStore::where('store_id', $storeId)->where('user_id', $followerId)->where('used', 0)->orderBy('created_at', 'desc');
                if($period && $period != "all"){
                    switch ($period) {
                        case 'today':
                            $stamp->whereBetween('datetime', [date('Y-m-d 00:00:00'), date('Y-m-d 23:59:59')]);
                            break;
                        case 'yesterday':
                            $yesterday = date('Y-m-d',strtotime("-1 days"));
                            $stamp->whereBetween('datetime', [$yesterday . ' 00:00:00', $yesterday . ' 23:59:59']);
                            break;
                        case 'this_week':
                            $stamp->where('datetime', '>', \DB::raw('DATE_SUB(NOW(), INTERVAL 1 WEEK)'));
                            break;
                        case 'this_month':
                            $stamp->where(\DB::raw('YEAR(datetime)'), '=', date('Y'));
                            $stamp->where(\DB::raw('MONTH(datetime)'), '=', date('n'));
                            break;
                        case 'last_month':
                            //$stamp->whereBetween('datetime', [\DB::raw('DATE_FORMAT(NOW() - INTERVAL 1 MONTH, \'%Y-%m-01 00:00:00\')'), \DB::raw('DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), \'%Y-%m-%d 23:59:59\')')]);
                            $stamp->whereRaw('`datetime` BETWEEN DATE_FORMAT(NOW() - INTERVAL 1 MONTH, \'%Y-%m-01 00:00:00\') AND DATE_FORMAT(LAST_DAY(NOW() - INTERVAL 1 MONTH), \'%Y-%m-%d 23:59:59\')');
                            break;
                        case 'current_year':
                            //$stamp->whereBetween('datetime', [\DB::raw('YEAR(datetime)'), \DB::raw('DATE_FORMAT(NOW()')]);
                            $stamp->where(\DB::raw('YEAR(datetime)'), '=', \DB::raw('YEAR(CURDATE())'));
                            break;
                        case 'last_year':
                            $stamp->where(\DB::raw('YEAR(datetime)'), '=', date('Y') - 1);
                            break;
                        default:
                    }

                }
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
    public function destroy($userId, $id)
    {
        //
        // Retrieve all the users in the database and return them
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $stamp = UserStampStore::find($id);
        $ms = "Item not found";
        $statusCode = Response::HTTP_NOT_FOUND;
        //$storeId = Input::get('store_id');
        $status = "fail";
        if($user && $stamp && ($user->id == $stamp->created_id)) {
            try{
                $statusCode = Response::HTTP_OK;
                $status = "success";
                $ms = "The stamp has just deleted";
                $stamp->delete();
            }catch(Exception $e){
                $ms = "500 error. do not delete";
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            }finally{
                return \Response::json(['status' => $status,'message' => $ms, 'statusCode' => $statusCode]);
            }
        }else{
            return \Response::json(['status' => $status,'message' => $ms, 'statusCode' => $statusCode]);
        }
    }

    /**
     * @param int $userId
     */
    public function discount($userId){
        $statusCode = Response::HTTP_CONFLICT;
        $status = 'fail';
        $ms = 'Sorry, The system can not discount';
        // Retrieve all the users in the database and return them
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $followerId = Input::get('follower_id');
        $storeId = Input::get('store_id');
        $quantityId = Input::get('quantity');
        // TODO: validation Request params
        if($user && $quantityId && $followerId && $storeId) {
            $stamp = UserStampStore::where('store_id', $storeId)->where('user_id', $followerId)->where('used', 0)->orderBy('created_at', 'desc');
            $stamps = $stamp->limit($quantityId)->get();

            foreach ($stamps as $stamp) {
                $stamp->used = 1;
                $stamp->save();
            }
            $status = "success";
            $statusCode = Response::HTTP_OK;
            $ms = "User's stamp has just removed";
        }

        return \Response::json(['status' => $status,'message' => $ms, 'statusCode' => $statusCode]);
    }
}
