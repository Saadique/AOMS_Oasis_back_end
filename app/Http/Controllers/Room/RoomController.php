<?php

namespace App\Http\Controllers\Room;

use App\Course;
use App\Http\Controllers\ApiController;
use App\Room;
use App\Services\ServiceGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends ApiController
{
    private $serviceGateway;

    public function __construct(ServiceGateway $serviceGateway)
    {
        $this->serviceGateway = $serviceGateway;
    }

    public function index()
    {
        return Room::all();
    }

    public function getActiveRooms() {
        $user = Auth::user();
        $rooms = Room::where('status','active')->get();
        return $rooms;
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $requstBody = $request->all();
        return $this->serviceGateway->roomService->createRoom($requstBody);
    }


    public function show(Room $room)
    {
        return $this->showOne($room);
    }

    public function update(Request $request, Room $room)
    {
        $user = Auth::user();
        $requestBody = $request->all();
        return $this->serviceGateway->roomService->updateRoom($requestBody, $room);
    }

    public function changeDeleteStatus($roomId,$status) {
        $user = Auth::user();
        $room = Room::findOrFail($roomId);
        $room->status = $status;
        $room->save();
        return $room;
    }

    public function destroy(Room $room)
    {

    }
}
