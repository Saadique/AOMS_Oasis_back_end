<?php


namespace App\Traits;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{

    public function successResponse($data, $code)
    {
        return response()->json($data,$code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        return $this->successResponse(['data' => $collection], $code );
    }

    protected function showOne(Model $model, $code = 201)
    {
        return $this->successResponse(['data' => $model], $code );
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(["message" => $message, 'code' => $code], $code);
    }
}
