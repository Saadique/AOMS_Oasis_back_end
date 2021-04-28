<?php


namespace App\Services\Course;


use App\Medium;
use App\Services\Service;

class MediumService extends Service
{
    public function createMedium($requestData) {
        $existsName = Medium::where('name', $requestData['name'])->first();
        if ($existsName!=null) {
            return $this->errorResponse("This medium name already exists", 400);
        }

        $existsShortName = Medium::where('short_name', $requestData['short_name'])->first();
        if ($existsShortName!=null) {
            return $this->errorResponse("This Short Name for Medium name already exists", 400);
        }

        $medium = Medium::create($requestData);

        return $this->showOne($medium);
    }

    public function getAllActiveMediums($requestBody) {

    }


    public function updateMedium($requestBody, Medium $medium) {
        $existsName = Medium::where([
            ['name', $requestBody['name']],
            ['id','!=',$medium->id]
        ])->first();
        if ($existsName!=null) {
            return $this->errorResponse("This medium name already exists", 400);
        }

        $existsShortName = Medium::where([
            ['id','!=',$medium->id],
            ['short_name', $requestBody['short_name']]
        ])->first();
        if ($existsShortName!=null) {
            return $this->errorResponse("This Short Name for Medium name already exists", 400);
        }

        $medium->name = $requestBody['name'];
        $medium->short_name = $requestBody['short_name'];
        $medium->description = $requestBody['description'];
        $medium->update();

        return $medium;
    }

    public function deleteMedium(Medium $medium) {
        $medium->status = 'deleted';
        $medium->update();
        return $medium;
    }

    public function activateMedium($mediumId) {
        $medium = Medium::findOrFail($mediumId);
        $medium->status = 'active';
        $medium->update();
        return $medium;
    }
}
