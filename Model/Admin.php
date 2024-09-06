<?php 

class Admin 
{
    private $id;
    private $userName;

    public function __construct($id, $userName)
    {
        $this->id = $id;
        $this->userName = $userName;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getUserName()
    {
        return $this->userName;
    }
}