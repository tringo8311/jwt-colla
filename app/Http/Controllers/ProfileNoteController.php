<?php
namespace App\Http\Controllers;

use App\Model\UserNote;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Input;
use Validator;
use JWTAuth;

class ProfileNoteController extends Controller
{
    protected $rules = [
        'product_code' => ['required', 'min:2'],
        'content' => ['required'],
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
    public function index(){
        $statusCode = Response::HTTP_OK;
        $response = [
            'data'  => [],
            'length' => 0
        ];
        // Retrieve all the users in the database and return them
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $keyword = Input::get('keyword');
        $pageSize = 100;
        try{
            if($user){
                $note = UserNote::where('user_id', $user->id)->orderBy('created_at', 'desc');
                if($keyword){
                    $note->where('content', 'like' ,"%$keyword%")->orWhere('barcode', 'like' ,"%$keyword%")->orWhere('product_code', 'like' ,"%$keyword%");
                }
                $notes = $note->limit($pageSize)->get();
                $data = array();
                foreach ($notes as $note) {
                    $data[] = $note->toArray();
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
                $input = Input::only('barcode', 'product_code', 'content');
                $input['user_id'] = $user->id;
                $newUserNote = UserNote::create($input);
                return \Response::json(['status' => 'success', 'data' => $newUserNote], $statusCode);
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
    public function update(Request $request, $userId, $id){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user) {
            $v = Validator::make($request->all(), $this->rules);
            $note = UserNote::find($id);
            if ($note && $v->passes()) {
                $data = Input::only('barcode', 'product_code', 'content');
                foreach ($data as $key => $value) {
                    if (null != $value) {
                        $note->$key = $value;
                    }
                }
                $note->save();
                return \Response::json([
                    'status' => "success",
                    'message' => "Your note has been updated. Thank You!"
                ], Response::HTTP_OK);
            }
        }
        return \Response::json([
            'status' => "fail",
            'message' => "Your session was expired"
        ], Response::HTTP_CONFLICT);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($user_id, $id)
    {
        //
        // Retrieve all the users in the database and return them
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $userNote = UserNote::find($id);
        $ms = "Item not found";
        $statusCode = 404;
        $status = "fail";
        if($user && $userNote && ($user->id == $userNote->user_id)) {
            try{
                $statusCode = 200;
                $status = "success";
                $ms = "Your note has just deleted";
                $userNote->delete();
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
