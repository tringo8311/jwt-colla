<?php
namespace App\Http\Controllers;

use App\Model\Slider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\HttpResponse;
use Illuminate\Support\Facades\Input;

use App\Http\Requests;
use App\Model\Store;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $statusCode = Response::HTTP_OK;
        $response = [
            'pageItems'  => [],
            'totalItems' => 0,
            'pageNumber' => 1,
            'pageSize' => 10
        ];
        try{
            $sliders = Slider::where('activated', '1')->orderBy('created_at', 'desc')->get();
            $store = null;
            foreach($sliders as $slide){
                $store = Store::find($slide->store_id);
                $response['pageItems'][] = [
                    'id' => $store->bID,
                    'title' => $store->title,
                    'address' => $store->address,
                    'state' => $store->state,
                    'city' => $store->city,
                    'zipcode' => $store->zipcode,
                    'phone' => $store->phone,
                    'email' => $store->email,
                    'website' => $store->website,
                ];
            }
        }catch (Exception $e){
            $statusCode = Response::HTTP_NOT_FOUND;
        }finally{
            return \Response::json($response, $statusCode);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
