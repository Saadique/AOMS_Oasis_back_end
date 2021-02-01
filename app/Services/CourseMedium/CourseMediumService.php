<?php


namespace App\Services\CourseMedium;


use App\CourseMedium;
use App\Services\Service;

class CourseMediumService extends Service
{
    public function getCourse($id) {
        $courseMedium = CourseMedium::where('id', $id)
            ->with('course','medium')
            ->get();
    return response()->json($courseMedium, 200);
    }
}
