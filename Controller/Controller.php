<?php

abstract class Controller 
{
    protected $access = null;
    public function __construct($access)
    {
        $this->access = $access;
    }
}