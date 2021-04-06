<?php

namespace App\Services\Lecture;

use App\LectureLessons;
use App\LessonMaterials;
use App\Services\Service;
use Illuminate\Http\Request;
use PhpParser\Node\Scalar\String_;

class LectureLessonsService extends Service
{

    public function createLesson($request) {
        $existingLesson = LectureLessons::where([
            ['lecture_id', $request['lecture_id']],
            ['name',$request['name']]
        ])->first();

        if ($existingLesson==null) {
            return LectureLessons::create($request);
        }else{
            return response("LESSON_EXISTS_WITH_SAME_NAME", 400);
        }
    }

    public function getLessonsByLecture($lecture_id){
        $lectureLessons = LectureLessons::where('lecture_id', $lecture_id)->get();
        return $lectureLessons;
    }

    public function storeLessonMaterial(Request $request){
        $exists = LessonMaterials::where('file_name', $request->input('file_name'))->first();
        if ($exists != null){
            return response()->json(['message' => 'FILE_WITH_SAME_NAME_EXISTS'], 400);
        }

        $file = $request->file('file');
        $uniqueName = uniqid();
        $extension = $file->extension();
        $fileName = $uniqueName . "." . $extension;
        $uploadPath = $request->file('file')->move(public_path("/lecture_materials"), $fileName);
        $filePath = '/lecture_materials/' . $fileName;

        $lessonMaterial = new LessonMaterials();
        $lessonMaterial->lesson_id = $request->input('lesson_id');
        $lessonMaterial->lecture_id = $request->input('lecture_id');
        $lessonMaterial->file_name = $request->input('file_name');
        $lessonMaterial->file_extension = $extension;
        $lessonMaterial->path = $filePath;
        $lessonMaterial->save();

        return response()->json(['message' => "SUCCESSFUL"], 200);
    }

    public function getLectureMaterialsOfLesson($lesson_id) {
        $lessons = LessonMaterials::where('lesson_id', $lesson_id)->get();
        return $lessons;
    }

    public function downloadFile($requestBody) {
        return response()->download(public_path($requestBody['file_path']),'image');
    }
}
