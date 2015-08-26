<?php
namespace App\Http\Controllers;

use App\Model\UserFeedback;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use Validator;
use JWTAuth;

class ProfileFeedbackController extends Controller
{
    protected $rules = [
        'rate' => ['required', 'min:1'],
        'content' => ['required'],
    ];
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        // Retrieve all the users in the database and return them
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if($user){
            //$notes = UserFeedback::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
            $feedbacks = $user->feedbacks()->orderBy('created_at', 'desc');
            if($storeId = Input::get("storeId")){
                $feedbacks->where("store_id", $storeId);
            }
            $data = $feedbacks->get();
            return \Response::json(['data' => $data]);
        }

        return \Response::json(['data' => []]);
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
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if($user) {
            $v = Validator::make($request->all(), $this->rules);
            if ($v->passes()) {
                $input = Input::only('rate', 'service', 'employee', 'content');
                $input['user_id'] = $user->id;
                $newUserFeedback = UserFeedback::create($input);
                return \Response::json(['status' => 'success', 'data' => $newUserFeedback]);
            }else{
                $ms = $v->messages();
                return \Response::json(['status' => "failure",'messages' => $ms]);
            }
        }
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
