<?php

class SejourController extends Controller
{
    public function addSejour($dateDebut, $dateFin, $idReservation)
    {
        return $this->access->addSejour($dateDebut, $dateFin, $idReservation);    
    }
    public function getSejourByReservation($reservation)
    {
        return $this->access->getSejourByReservation($reservation);
    }
    public function updateSejour($sejour)
    {
        return $this->access->updateSejour($sejour);
    }
    public function getSejourById($id)
    {
        return $this->access->getSejourById($id);
    }
}