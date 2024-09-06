<?php

class Sejour
{
    private $id;
    private $dateDebut;
    private $dateFin;
    private $reservation;

    public function __construct($id, $dateDebut, $dateFin, $reservation)
    {
        $this->id = $id;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->reservation = $reservation;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getDateDebut()
    {
        return $this->dateDebut;
    }
    public function getDateFin()
    {
        return $this->dateFin;
    }
    public function setDateDebut($date)
    {
        $this->dateDebut = $date;
    }
    public function setDateFin($date)
    {
        $this->dateFin = $date;
    }
    public function getDureeSejour()
    {
        $debut = DateTime::createFromFormat("Y-m-d", $this->dateDebut);
        $fin = DateTime::createFromFormat("Y-m-d", $this->dateFin);
        $interval = $debut->diff($fin);
        return intval($interval->format("%R%a days"));
    }
}