<?php

namespace App\Http\Controllers\Lecture;

use App\Http\Controllers\Controller;
use App\LessonMaterials;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $user = Auth::user();
        return $this->serviceGateway->lectureLessonService->storeLessonMaterial($request);
    }

    public function getMaterialsByLesson($lesson_id) {
        $user = Auth::user();
        return $this->serviceGateway->lectureLessonService->getLectureMaterialsOfLesson($lesson_id);
    }

    public function downloadFile(Request $request) {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->lectureLessonService->downloadFile($requestBody);
    }

    public function show(LessonMaterials $lessonMaterials)
    {
        //
    }


    public function updateLectureMaterial(Request $request, $materialId) {
        $user = Auth::user();
        $file = $request->file('file');
        $uniqueName = uniqid();
        $extension = $file->extension();
        $fileName = $uniqueName . "." . $extension;
        $uploadPath = $request->file('file')->move(public_path("/lecture_materials"), $fileName);
        $filePath = '/lecture_materials/' . $fileName;


        $material = LessonMaterials::findOrFail($materialId);
        $material->file_extension = $extension;
        $material->path = $filePath;
        $material->save();

        return response()->json(['message' => "SUCCESSFUL"], 200);
    }

    public function updateLectureMaterialInfo(Request $request, $lessonMaterialId)
    {
        $user = Auth::user();
        $request = $request->all();
        $exists = LessonMaterials::where([
            ['file_name',$request['file_name']],
            ['id','!=',$lessonMaterialId]
        ])->first();

        if ($exists != null){
            return response()->json(['message' => 'File With Same Name Already Exists!'], 400);
        }

        $lessonMaterial = LessonMaterials::findOrFail($lessonMaterialId);
        $lessonMaterial->file_name = $request['file_name'];
        $lessonMaterial->save();
        return $lessonMaterial;
    }

    public function getAllMaterialsWithLessons($lecture_id) {
        $user = Auth::user();
        return $this->serviceGateway->lectureLessonService->findAllMaterialsWithLessons($lecture_id);
    }


    public function destroy(LessonMaterials $lessons_material)
    {
        $user = Auth::user();
        DB::delete("DELETE FROM lesson_materials WHERE id=$lessons_material->id");
        return response()->json(['message' => "SUCCESSFUL"], 200);
    }
}
