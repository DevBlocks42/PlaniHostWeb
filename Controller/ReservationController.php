<?php

class ReservationController extends Controller
{
    public function getReservations()
    {
        return $this->access->getReservations();
    }
    public function getReservationsOptimised($limit, $offset)
    {
        return $this->access->getReservationsOptimised($limit, $offset);
    }
    public function getReservationById($resId)
    {
        return $this->access->getReservationById($resId);
    }
    public function getClientReservations($client)
    {
        return $this->access->getClientReservations($client);   
    }
    public function getRoomCalendar($roomId)
    {
        return $this->access->getRoomCalendar($roomId);
    }
    public function getRoomCalendarOptimised($roomId, $from, $to)
    {
        return $this->access->getRoomCalendarOptimised($roomId, $from, $to);
    }
    public function isRoomAvailable($roomId, $from, $to)
    {
        return $this->access->isRoomAvailable($roomId, $from, $to);
    }
    public function addReservation($nbPersonnes, $client)
    {
        return $this->access->addReservation($nbPersonnes, $client);
    }
    public function deleteReservation($id)
    {
        return $this->access->deleteReservation($id);
    }
}