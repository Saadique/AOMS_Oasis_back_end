<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Subject\SubjectStoreRequest;
use App\Services\ServiceGateway;
use App\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        $user = Auth::user();
        $subjects = Subject::where('status', 'active')->get();
        return $this->showAll($subjects);
    }

    public function getAllByCourseMedium($courseMediumId) {
        $user = Auth::user();
        return $this->serviceGateway->subjectService->getAllSubjectsOfOneCourseMedium($courseMediumId);
    }

    public function getAllSubjectsByCM($courseMediumId){
        $user = Auth::user();
        $subjects = Subject::where('course_medium_id',$courseMediumId)->get();
        return $this->showAll($subjects);
    }


    public function store(SubjectStoreRequest $request)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->subjectService->createSubject($requestBody);
    }


    public function show(Subject $subject)
    {
        $user = Auth::user();
        return $this->showOne($subject);
    }

    public function update(Request $request, Subject $subject)
    {
        $user = Auth::user();
        $request = $request->all();
        return $this->serviceGateway->subjectService->updateSubject($request, $subject);
    }

    public function changeDeleteStatus($subjectId,$status) {
        $user = Auth::user();
        $subject = Subject::findOrFail($subjectId);
        $subject->status = $status;
        $subject->save();
        return $subject;
    }


    public function destroy(Subject $subject)
    {
        //
    }
}
