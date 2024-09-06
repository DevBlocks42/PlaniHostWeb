<?php 

class RoomController extends Controller
{
    public function getMaxRooms() 
    {
        return $this->access->getMaxRooms();
    }
    public function getRoomList($limit, $offset)
    {
        return $this->access->getRoomList($limit, $offset);
    }
    public function getFullRoomList()
    {
        return $this->access->getFullRoomList();
    }
    public function getRoomInfos($id)
    {
        return $this->access->getRoomInfos($id);
    }
    public function addRoom($room)
    {
        return $this->access->addRoom($room);
    }
    public function removeRoomImage($roomid, $imageName)
    {
        return $this->access->removeRoomImage($roomid, $imageName);
    }
    public function removeRooms($roomids)
    {
        return $this->access->removeRooms($roomids);
    }
    public function getRoomBySejour($sejour)
    {
        return $this->access->getRoomBySejour($sejour);
    }
}