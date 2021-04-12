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
        $mediums = Medium::where('status','active')->get();
        return $this->showAll($mediums);
    }

    public function getAllMediums() {
        $mediums = Medium::all();
        return $this->showAll($mediums);
    }

    public function activateMedium($mediumId) {
        return $this->serviceGateway->mediumService->activateMedium($mediumId);
    }



    public function store(Request $request)
    {
        $requestData = $request->all();
        return $this->serviceGateway->mediumService->createMedium($requestData);
    }


    public function show(Medium $medium)
    {
        return $this->showOne($medium);
    }



    public function update(Request $request, Medium $medium)
    {
        $requestBody = $request->all();
        return $this->serviceGateway->mediumService->updateMedium($requestBody, $medium);
    }


    public function destroy(Medium $medium)
    {
        return $this->serviceGateway->mediumService->deleteMedium($medium);
    }
}
