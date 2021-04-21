<?php


namespace App\Services\Room;
use App\Room;
use App\Services\Service;

class RoomService extends Service
{
    public function createRoom($requestBody){
        $nameExists = Room::where('name', $requestBody['name'])->first();

        if ($nameExists){
            return response(["message"=>"A Room With This Name Already Exists"], 400);
        }
        $room = Room::create($requestBody);
        return $room;
    }

    public function updateRoom($requestBody, $room)
    {
        $nameExists = Room::where([
            ['name', $requestBody['name']],
            ['id', '!=', $room->id]
        ])->first();

        if ($nameExists) {
            return response(["message" => "A Room With This Name Already Exists"], 400);
        }

        $room->name = $requestBody['name'];
        $room->no_of_seats = $requestBody['no_of_seats'];
        $room->description = $requestBody['description'];
        $room->update();
        return $room;
    }
}
