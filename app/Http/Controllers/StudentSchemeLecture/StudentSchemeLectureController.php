<?php

namespace App\Http\Controllers\StudentSchemeLecture;

use App\Http\Controllers\Controller;
use App\Services\ServiceGateway;
use App\StudentSchemeLecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSchemeLectureController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $studentSchemeLecture = $request->all();
        $this->serviceGateway->studentSchemeLectureService->storeStudentSchemeLecture($studentSchemeLecture);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StudentSchemeLecture  $studentSchemeLecture
     * @return \Illuminate\Http\Response
     */
    public function show(StudentSchemeLecture $studentSchemeLecture)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StudentSchemeLecture  $studentSchemeLecture
     * @return \Illuminate\Http\Response
     */
    public function edit(StudentSchemeLecture $studentSchemeLecture)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StudentSchemeLecture  $studentSchemeLecture
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StudentSchemeLecture $studentSchemeLecture)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StudentSchemeLecture  $studentSchemeLecture
     * @return \Illuminate\Http\Response
     */
    public function destroy(StudentSchemeLecture $studentSchemeLecture)
    {
        //
    }
}
