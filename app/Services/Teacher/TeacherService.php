<?php

namespace App\Services\Teacher;

use App\Services\Service;
use App\Teacher;

class TeacherService extends Service
{

    public function createTeacher($requestBody) {
        $teacher = Teacher::create($requestBody);
        return $this->showOne($teacher);
    }

    public function findAllLecturesByTeacher($teacherId){
        $teacher = Teacher::findOrFail($teacherId);
        return $teacher->lectures;
    }
}
