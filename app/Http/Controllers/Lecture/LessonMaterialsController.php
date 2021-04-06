<?php

namespace App\Http\Controllers\Lecture;

use App\Http\Controllers\Controller;
use App\LessonMaterials;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class LessonMaterialsController extends Controller
{
    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }


    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
//        $file = $request->file('file');
//        print_r($request->file('file'));
//        print_r($request);
        return $this->serviceGateway->lectureLessonService->storeLessonMaterial($request);
    }

    public function getMaterialsByLesson($lesson_id) {
        return $this->serviceGateway->lectureLessonService->getLectureMaterialsOfLesson($lesson_id);
    }

    public function downloadFile(Request $request) {
        $requestBody = $request->all();
        return $this->serviceGateway->lectureLessonService->downloadFile($requestBody);
    }

    public function show(LessonMaterials $lessonMaterials)
    {
        //
    }




    public function update(Request $request, LessonMaterials $lessonMaterials)
    {
        //
    }


    public function destroy(LessonMaterials $lessonMaterials)
    {
        //
    }
}
