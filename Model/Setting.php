<?php 

class Setting implements JsonSerializable
{
    private $id;
    private $clef;
    private $valeur;
    public function __construct($id, $clef, $valeur)
    {
        $this->id = $id;
        $this->clef = $clef;
        $this->valeur = $valeur;
    }
    public function getId()
    {
        return $this->id;
    }
    public function getClef()
    {
        return $this->clef;
    }
    public function getValeur()
    {
        return $this->valeur;
    }
    public function jsonSerialize() : mixed
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}