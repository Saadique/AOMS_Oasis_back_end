<?php

namespace App\Http\Controllers\Medium;

use App\Http\Controllers\ApiController;
use App\Medium;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;

class MediumController extends ApiController
{
    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }


    public function index()
    {
        $mediums = Medium::all();
        return $this->showAll($mediums);
    }



    public function store(Request $request)
    {
        $requestData = $request->all();
        $medium = Medium::create($requestData);
        return $this->showOne($medium);
    }


    public function show(Medium $medium)
    {
        return $this->showOne($medium);
    }



    public function update(Request $request, Medium $medium)
    {

    }


    public function destroy(Medium $medium)
    {
        //
    }
}
