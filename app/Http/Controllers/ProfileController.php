<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Input;

use JWTAuth;
use Validator;
use Mail;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        //$this->middleware('jwt.auth', ['except' => ['update']]);
        $this->middleware('jwt.auth');
        //DB::enableQueryLog();
    }

    public function index(){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $data = [
            'id' => $user->id,
            'email' => $user->email,
            'username' => $user->username,
            'code' => $user->code,
            'role' => $user->role,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'mobile' => $user->mobile,
            'address' => $user->address,
            'zipcode' => $user->zipcode,
            'store_id' => null
        ];
        //print_r($user->stores);
        if($user->stores && !$user->stores->isEmpty()){
            $data['store_id'] = $user->stores->first()->bID;
        }
        $data['registered_at'] = $user->created_at->toDateTimeString();

        return \Response::json([
            'data' => $data
        ]);
    }
    /**
     * Update the user's profile.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        // $request->user() returns an instance of the authenticated user...
        if ($user = $request->user()) {
            $data = Input::only('first_name', 'last_name', 'mobile', 'address', 'zipcode');
            foreach($data as $key => $value){
                if(null!=$value){
                    $user->$key = $value;
                }
            }
            $user->save();
            return \Response::json([
                'status' => "success",
                'message' => "Your profile has been updated. Thank You!"
            ]);
        }else{
            return \Response::json([
                'status' => "fail",
                'message' => "Your session was expired"
            ]);
        }
    }

    /**
     * Get first store
     * @return mixed
     */
    public function place(){
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $response = []; $statusCode = 404;
        try{
            if($user && $user->stores && !$user->stores->isEmpty()){
                $store = $user->stores->first();
                $statusCode = 200;
                $response = ["data" => [
                    'id' => (int)$store->bID,
                    'title' => $store->title,
                    'address' => $store->address,
                    'state' => $store->state,
                    'city' => $store->city,
                    'zipcode' => $store->zipcode,
                    'phone' => $store->phone,
                    'email' => $store->email,
                    'website' => $store->website,
                    'business_hour' => $store->business_hour
                ]];
            }
        }catch(Exception $e){
            $response = [
                "error" => "File doesn`t exists"
            ];
            $statusCode = 404;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }

    /**
     * @return json value
     */
    public function favourite()
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $response = ["status" => "success"];
        $statusCode = 404;
        try {
            $storeId = Input::only("store_id");
            if($user && $storeId) {
                if($user->stores && !$user->stores->isEmpty() && ($user->stores->count() > 5)){
                    $firstStore = $user->stores->first();
                    $user->stores()->detach($firstStore->bID);
                }
                $user->stores()->attach($storeId);
                $statusCode = 200;
                $response = ["status" => "success"];
            }
        } catch (Exception $e) {
            $response = [
                "status" => "fail",
                "error" => "500 error"
            ];
            $statusCode = 500;
        } finally {
            return \Response::json($response, $statusCode);
        }
    }
    /**
     * @param Request $request
     * @return mixed
     */
    public function contact(Request $request){
        //Get all the data and store it inside Store Variable
        $data = Input::only('full_name', 'email', 'subject', 'message');

        //Validation rules
        $rules = array (
            'full_name' => 'required',
            'email' => 'required|email',
            'subject' => 'required|min:2',
            'message' => 'required|min:5'
        );
        //Validate data
        $validator = Validator::make ($data, $rules);
        //If everything is correct than run passes.
        if ($validator -> passes()){
            Mail::send('emails.contact', $data, function($message) use ($data){
                $message->from(env('CUSTOMER_CONTACT_EMAIL_FROM'), env('CUSTOMER_CONTACT_NAME'));
                $message->to(env('CUSTOMER_CONTACT_EMAIL_TO'), env('CUSTOMER_CONTACT_NAME'))->cc(env('CUSTOMER_CONTACT_EMAIL_CC'))->subject(env('CUSTOMER_CONTACT_SUBJECT'));
            });
            return \Response::json([
                'status' => "success",
                'message' => "Your message has been sent. Thank You!"
            ]);
        }else{
            $messages = $validator->messages();
            return \Response::json([
                'status' => "fail",
                'message' => $messages
            ]);
        }
    }
}
