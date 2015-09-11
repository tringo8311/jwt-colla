<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\HttpResponse;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;

use App\Http\Requests;
use Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\User;
use App\UserObserver;


class UserController extends Controller
{

    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['except' => ['signup']]);
    }

    public function index()
    {
        $users = User::all();
        return $users;
    }

    public function signup(Request $request){
        $statusCode = Response::HTTP_OK;
        $response = [
            'status'  => 'success',
            'message' => [],
            'data' => ''
        ];
        $credentials = Input::only('username', 'email', 'first_name', 'last_name', 'mobile', 'address', 'zip_code', 'password', 'password_confirmation');
        $validator = Validator::make(
            $credentials, [
                'username' => 'required|alpha_dash|unique:users',
                'first_name' => 'required|min:2',
                'last_name' => 'required|min:2',
                'email' => 'required|email|min:9|unique:users',
                'password' => 'required|min:6|confirmed',
                'password_confirmation'=> 'required|min:6'
            ]);
        $preferType = Input::get('prefer_type');
        if(!$validator->fails()){
            try {
                $credentials['password'] = Hash::make($credentials['password']);
                if($preferType && $preferType == "owner"){
                    $credentials['role'] = "owner";
                    $credentials['activated'] = 0;
                }else{
                    $credentials['role'] = "customer";
                }
                User::observe(new UserObserver());
                $user = User::create($credentials);
                $response['data'] = $user;
                $response['message'] = '';
                //$token = JWTAuth::fromUser($user);
                //$response['token'] = compact($token);
            } catch (Exception $e) {
                $response['message'] = ['User already exists.'];
                $response['status'] = 'fail';
                $statusCode = Response::HTTP_CONFLICT;
            } finally {
                return \Response::json($response, $statusCode);
            }
        }else{
            $messages = $validator->messages();
            $response['message'] = $messages;
            $response['status'] = 'fail';
            $statusCode = Response::HTTP_CONFLICT;
            return \Response::json($response, $statusCode);
        }
    }
}