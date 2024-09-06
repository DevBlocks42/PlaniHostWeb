<?php 

abstract class Access 
{
    protected $database = null;
    public function __construct($database)
    {
        $this->database = $database;
    }
}