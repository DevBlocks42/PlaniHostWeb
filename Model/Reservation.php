<?php

class Reservation 
{
    private $id;
    private $nbPersonnes;
    private $client;
    public function __construct($id, $nbPersonnes, $client)
    {
        $this->id = $id;
        $this->nbPersonnes = $nbPersonnes;
        $this->client = $client;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getClient()
    {
        return $this->client;
    }
}