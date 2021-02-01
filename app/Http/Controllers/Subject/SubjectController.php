<?php

namespace App\Http\Controllers\Subject;

use App\Http\Controllers\ApiController;
use App\Http\Requests\Subject\SubjectStoreRequest;
use App\Services\ServiceGateway;
use App\Subject;
use Illuminate\Http\Request;

class SubjectController extends ApiController
{

    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        $subjects = Subject::all();
        return $this->showAll($subjects);
    }

    public function getAllByCourseMedium($courseMediumId) {
        return $this->serviceGateway->subjectService->getAllSubjectsOfOneCourseMedium($courseMediumId);
    }


    public function store(SubjectStoreRequest $request)
    {
        $requestBody = $request->all();
        return $this->serviceGateway->subjectService->createSubject($requestBody);
    }


    public function show(Subject $subject)
    {
        return $this->showOne($subject);
    }

    public function update(Request $request, Subject $subject)
    {
        return $this->serviceGateway->subjectService->updateSubject($request, $subject);
    }


    public function destroy(Subject $subject)
    {
        //
    }
}
