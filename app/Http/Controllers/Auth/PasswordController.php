<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Mail\Message;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;

use Validator;


class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postEmail(Request $request){
        $statusCode = Response::HTTP_OK;
        $response = [
            'status' => 'fail',
            'message' => null
        ];
        $rules = ['email' => 'required|email'];
        $data = [ 'email' => $request->only('email')];
        //Validate data
        $validator = Validator::make ($request->all(), $rules);
        //If everything is correct than run passes.
        try{
            if ($validator -> passes()){
                //$data['username'] = "dohoie";
                $resetResponse = Password::sendResetLink($data, function(Message $message) {
                    $message->subject($this->getEmailSubject());
                    $message->sender(env('SYSTEM_EMAIL_FROM'));
                });
                switch ($resetResponse) {
                    case Password::RESET_LINK_SENT:
                        //return redirect()->back()->with('status', trans($response));
                        $response['status'] = 'success';
                        $response['message'] = trans($resetResponse);
                        break;
                    case Password::INVALID_USER:
                        $response['status'] = 'fail';
                        $response['message'] = trans($resetResponse);
                        //return redirect()->back()->withErrors(['email' => trans($response)]);
                        break;
                    default:
                        $response['status'] = 'fail';
                        $response['message'] = trans($resetResponse);
                        break;
                }
            }else{
                $messages = $validator->messages();
                $response['message'] = $messages;
            }
        }catch (Exception $e){
            $statusCode = \Response::HTTP_CONFLICT;
            $response['message'] = $e->getMessage();
        }finally{
            return \Response::json($response, $statusCode);
        }
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postReset(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ]);

        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function ($user, $password) {
            $this->resetPassword($user, $password);
        });


        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect($this->redirectPath());

            default:
                return redirect()->back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => trans($response)]);
        }
    }
}
