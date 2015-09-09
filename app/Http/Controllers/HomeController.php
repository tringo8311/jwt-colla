<?php
namespace App\Http\Controllers;

use Illuminate\Http\HttpResponse;

use App\Http\Requests;
use Validator;
use JWTAuth;


class HomeController extends Controller
{

    public function index()
    {
        return view('home', []);
    }

}