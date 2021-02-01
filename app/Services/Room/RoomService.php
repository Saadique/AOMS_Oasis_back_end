<?php


namespace App\Services\Room;
use App\Room;
use App\Services\Service;

class RoomService extends Service
{
    public function createRoom($requestBody){
        $room = Room::create($requestBody);
        return $room;
    }

    public function updateRoom($requestBody, $room) {
        $room->name = $requestBody['name'];
        $room->no_of_seats = $requestBody['no_of_seats'];
        $room->description = $requestBody['description'];
        $room->status = $requestBody['status'];
        $room->update();
        return $room;
    }
}
