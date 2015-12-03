<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use App\User;
use App\Http\Requests;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthenticateController extends Controller
{
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        $this->middleware('jwt.auth', ['except' => ['authenticate', 'refresh']]);
        $this->middleware('jwt.refresh', ['except' => ['authenticate']]);
    }

    public function index()
    {
        // Retrieve all the users in the database and return them
        $users = User::all();
        return $users;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->only('remember') ? true : false;

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }else{
                // Check account is activated or not
                $user = JWTAuth::toUser($token);
                if($user->activated != "1"){
                    return response()->json(['error' => 'account_deactivated'], 403);
                }
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function facebook(Request $request)
    {

    }
    /**
     * Refresh an expired token.
     *
     * @param Request $request
     *
     * @return string
     */
    public function refresh(Request $request)
    {
        // if no errors are encountered we can return a JWT
        return response()->json('{status:"refresh"}');
    }
}