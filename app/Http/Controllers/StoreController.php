<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\HttpResponse;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Model\Store;
use JWTAuth;
use Validator;

class StoreController extends Controller
{
    public function __construct()
    {
        // Apply the jwt.auth middleware to all methods in this controller
        // except for the authenticate method. We don't want to prevent
        // the user from retrieving their token if they don't already have it
        //$this->middleware('jwt.auth', ['except' => ['update']]);
        $this->middleware('jwt.auth', ['except' => ['near']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $statusCode = 200;
        $response = [
            'data'  => [],
            'length' => 0
        ];
        $keyword = Input::get('keyword');
        try{
            $stores = Store::where('active', '1')->orderBy('created_at', 'desc');
            if($keyword){
                $stores->where('content', 'like' ,"%$keyword%")->orWhere('title', 'like' ,"%$keyword%")
                    ->orWhere('service', 'like' ,"%$keyword%")
                    ->orWhere('zipcode', 'like' ,"%$keyword%");
            }
            $stores = $stores->limit(self::$limit)->offset(0)->get();
            foreach($stores as $store){
                $response['data'][] = [
                    'id' => $store->bID,
                    'title' => $store->title,
                    'address' => $store->address,
                    'state' => $store->state,
                    'city' => $store->city,
                    'zipcode' => $store->zipcode,
                    'phone' => $store->phone,
                    'email' => $store->email,
                    'website' => $store->website,
                    'business_hour' => $store->business_hour
                ];
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $statusCode = 200;
        $response = ["data" > ""];
        try{
            $store = Store::find($id);
            $response = [ "data" => [
                'id' => (int)$store->bID,
                'title' => $store->title,
                'slogan' => $store->slogan,
                'company_name' => $store->company_name,
                'address' => $store->address,
                'state' => $store->state,
                'city' => $store->city,
                'zipcode' => $store->zipcode,
                'phone' => $store->phone,
                'email' => $store->email,
                'website' => $store->website,
                'business_hour' => $store->business_hour,
                'latitude' => $store->latitude,
                'longtitude' => $store->longtitude,
                'overview' => $store->overview,
                'service' => $store->service,
                'location' => $store->location,
                'content' => $store->content
            ]];
        }catch(Exception $e){
            $response = [
                "error" => "File doesn`t exists"
            ];
            $statusCode = Response::HTTP_NOT_FOUND;
        }finally{
            return \Response::json($response, $statusCode);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    //public function update(Request $request, $id)
    public function update($id)
    {
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        if ($user) {
            $data = Input::only('title', 'company_name', 'address', 'city', 'state', 'zipcode', 'phone', 'fax', 'website', 'email', 'latitude', 'longtitude', 'business_hour', 'overview', 'content');
            $store = Store::find($id);
            if($store){
                foreach($data as $key => $value){
                    if(null!=$value){
                        $store->$key = $value;
                    }
                }
                $store->save();
                return \Response::json([
                    'status' => "success",
                    'message' => "Your business has been updated. Thank You!"
                ]);
            }
        }else{
            return \Response::json([
                'status' => "fail",
                'message' => "Your session was expired"
            ]);
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

    /**
     * @return mixed
     */
    public function near(){
        $lat = Input::get('lat');
        $lng = Input::get('lng');
        $radius = Input::get('radius');
        $result = Store::querySearch($lat, $lng, $radius);

        // Start XML file, create parent node
        $dom = new \DOMDocument("1.0");
        $node = $dom->createElement("markers");
        $parnode = $dom->appendChild($node);

        // Iterate through the rows, adding XML nodes for each
        foreach ($result as $row){
            $node = $dom->createElement("marker");
            $newnode = $parnode->appendChild($node);
            $newnode->setAttribute("id", $row->bID);
            $newnode->setAttribute("title", $row->title);
            $newnode->setAttribute("slogan", $row->slogan);
            $newnode->setAttribute("address", $row->address);
            $newnode->setAttribute("city", $row->city);
            $newnode->setAttribute("state", $row->state);
            $newnode->setAttribute("zipcode", $row->zipcode);
            $newnode->setAttribute("phone", $row->phone);
            $newnode->setAttribute("fax", $row->fax);
            $newnode->setAttribute("email", $row->email);
            $newnode->setAttribute("website", $row->website);
            $newnode->setAttribute("website", $row->website);
            $newnode->setAttribute("store_link", $row->store_link);
            $newnode->setAttribute("lat", $row->lat);
            $newnode->setAttribute("lng", $row->lng);
            $newnode->setAttribute("distance", $row->distance);
        }
        $result = $dom->saveXML();

        return \Response::make($result)->header('Content-Type', 'application/xml');
    }

    /**
     *
     */
    public function fetch_offers($store_id){
        $statusCode = 200;
        $response = [
            'data'  => [],
            'length' => 0
        ];
        try{
            $offers = Store::find($store_id)->offers()->where("activated", 1)->orderBy('start_time', 'desc')->get();
            //self::get_last_query();
            $response["length"] = sizeof($offers);
            $response["data"] = $offers;
        }catch (Exception $e){
            $statusCode = Response::HTTP_CONFLICT;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
    /**
     *
     */
    public function fetch_customers($store_id){
        $statusCode = 200;
        $response = [
            'data'  => [],
            'length' => 0
        ];
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $keyword = Input::get('keyword');
        try{
            $store = Store::find($store_id)->users()->where("user_id", "!=", $user->id)->orderBy('created_at', 'desc');
            if($keyword){
                $store = $store->where('first_name', 'like' ,"%$keyword%")->orWhere('code', 'like' ,"%$keyword%");
            }
            $users = $store->get();
            $customers = array();
            foreach ($users as $user) {
                $customers[] = $user->toArray();
            }
            $response["length"] = sizeof($customers);
            $response["data"] = $customers;
        }catch (Exception $e){
            $statusCode = 400;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }
}
